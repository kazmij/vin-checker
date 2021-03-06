<?php

namespace App\AdminBundle\Repository;

use App\AdminBundle\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Container;

/**
 * ModelTrimRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ModelTrimRepository extends ContentRepository
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
        $this->request = $container->get('request_stack')->getCurrentRequest();
    }

    public function getChoices($modelId = null) {
        if($this->request->get('model') || $modelId) {
            $qb = $this->createQueryBuilder('m');
            $choices = $qb->andWhere($qb->expr()->isNotNull('m.name'))
                ->andWhere($qb->expr()->neq('m.name', $qb->expr()->literal('')))
                ->andWhere($qb->expr()->eq('m.model', $modelId ? $modelId : $this->request->get('model')))
                ->getQuery()
                ->getResult();
        } else {
            $qb = $this->createQueryBuilder('m');
            $choices = $qb->andWhere($qb->expr()->isNotNull('m.name'))
                ->andWhere($qb->expr()->neq('m.name', $qb->expr()->literal('')))
                ->getQuery()
                ->getResult();
        }

        $data = [];
        foreach ($choices as $choice) {
            $data[$choice->getName()] = $choice->getId();
        }

        return $data;
    }

}
