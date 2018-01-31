<?php

namespace App\AdminBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Symfony\Component\DependencyInjection\Container;
use App\AdminBundle\Entity\Content;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Annotations\AnnotationReader;
use App\AdminBundle\Annotation\Xss\AllowedTags;
use Doctrine\Common\Util\ClassUtils;

class DoctrineSubscriber implements EventSubscriber
{

    /**
     * @var array
     */
    CONST ALLOWED_HTML_TAGS = [];

    /**
     * @var Container
     */
    private $container;

    private $cacheDriver;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
        $this->cacheDriver = $container->get('doctrine.orm.default_result_cache');
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'preFlush',
            'onFlush',
        );
    }

    /**
     * Change entity before store in the database
     *
     * @param PreFlushEventArgs $args
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $requestStack = $this->container->get('request_stack');
        $currentRequest = $requestStack->getCurrentRequest();
    }

    /**
     * Change entity before store in the database
     *
     * @param PreFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $this->invalidateCacheIfNeeded($entity);
            $this->checkEntityForXss($em, $entity);
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $this->invalidateCacheIfNeeded($entity);
            $this->checkEntityForXss($em, $entity);
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            $this->invalidateCacheIfNeeded($entity);
        }
    }

    private function invalidateCacheIfNeeded($entity)
    {
        $classes = [new Content(), $entity];
        foreach ($classes as $object) {
            $cacheKey = 'pagination_' . get_class($object);
            if ($this->cacheDriver->contains($cacheKey)) {
                $this->cacheDriver->delete($cacheKey);
            }

            $idCacheSlugKey = 'id_by_slug_' . get_class($object);
            if ($this->cacheDriver->contains($idCacheSlugKey)) {
                $this->cacheDriver->delete($idCacheSlugKey);
            }

            $cacheKey = 'seo_settings_' . get_class($object);
            if ($this->cacheDriver->contains($cacheKey)) {
                $this->cacheDriver->delete($cacheKey);
            }

            $idCacheSlugKey = 'home_cards_' . get_class($object);
            if ($this->cacheDriver->contains($idCacheSlugKey)) {
                $this->cacheDriver->delete($idCacheSlugKey);
            }

            if (method_exists($entity, 'getType')) {
                $typeCacheId = 'find_one_by_type_' . $entity->getType() . get_class($object);
                if ($this->cacheDriver->contains($typeCacheId)) {
                    $this->cacheDriver->delete($typeCacheId);
                }
            }

            if (method_exists($entity, 'getPlaces')) {
                foreach ($entity->getPlaces() as $place) {
                    $typeCacheId = 'banner_place_' . $place->getType() . get_class($object);
                    if ($this->cacheDriver->contains($typeCacheId)) {
                        $this->cacheDriver->delete($typeCacheId);
                    }
                }
            }
        }
    }

    /**
     * XSS protection
     *
     * @param EntityManager $em
     * @param mixed $entity
     */
    private function checkEntityForXss(EntityManager $em, $entity)
    {
        $annotationReader = new AnnotationReader();
        $entityClass = ClassUtils::getClass($entity);
        $fieldMappings = $em->getClassMetadata($entityClass)->fieldMappings;
        foreach ($em->getUnitOfWork()->getEntityChangeSet($entity) AS $propertyName => $propertyChangeSet) {
            if ($propertyName !== 'description') {
                if (isset($fieldMappings[$propertyName]['type']) && in_array($fieldMappings[$propertyName]['type'], array('string', 'text'))) {
                    $reflectionProperty = new \ReflectionProperty(isset($fieldMappings[$propertyName]['inherited']) ? $fieldMappings[$propertyName]['inherited'] : $entityClass, $propertyName);
                    /** @var AllowedTags $allowedTagsAnnotation */
                    $allowedTagsAnnotation = $annotationReader->getPropertyAnnotation(
                        $reflectionProperty,
                        AllowedTags::class
                    );
                    $allowedTags = $allowedTagsAnnotation ? $allowedTagsAnnotation->getTagNames() : self::ALLOWED_HTML_TAGS;
                    $propertyGetter = "get" . ucfirst($propertyName);
                    $propertySetter = "set" . ucfirst($propertyName);
                    if (method_exists($entity, $propertySetter) && method_exists($entity, $propertyGetter) && $entity->{$propertyGetter}()) {
                        $entity->{$propertySetter}(strip_tags($entity->{$propertyGetter}(), implode("", $allowedTags)));
                    }
                }
            }
        }
    }
}