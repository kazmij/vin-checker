<?php

namespace App\AdminBundle\Repository;

use Symfony\Component\DependencyInjection\Container;
use App\AdminBundle\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use App\AdminBundle\Entity\Setting;

/**
 * SettingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SettingRepository extends ContentRepository
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

    /**
     * @param Request $request
     */
    public function getPaginationQueryBuilder(Request $request)
    {
        $qb = $this->createQueryBuilder('s');
        $qb
            ->leftJoin('s.translations', 't')
            ->where($qb->expr()->eq('t.locale', $qb->expr()->literal($request->getLocale())));

        if ($request->get('query')) {
            $searchArr = explode(' ', trim($request->get('query')));
            $conditions = [];

            foreach ($searchArr as $search) {
                if (strlen($search) >= 3) {
                    $conditions[] = $qb->expr()->like('t.name', $qb->expr()->literal('%' . $search . '%'));
                    $conditions[] = $qb->expr()->like('t.description', $qb->expr()->literal('%' . $search . '%'));
                }
            }

            if (!empty($conditions)) {
                $qb->andWhere(implode(' OR ', $conditions));
            }
        }

        if ($request->get('from')) {
            $date = \DateTime::createFromFormat('Y-m-d H:i', $request->get('from'));
            if ($date) {
                $qb->andWhere($qb->expr()->gte('s.createdAt', $qb->expr()->literal($date->format('Y-m-d H:i'))));
            }
        }

        if ($request->get('to')) {
            $date = \DateTime::createFromFormat('Y-m-d H:i', $request->get('to'));
            if ($date) {
                $qb->andWhere($qb->expr()->lte('s.createdAt', $qb->expr()->literal($date->format('Y-m-d H:i'))));
            }
        }

        if ($request->get('active')) {
            $qb->andWhere($qb->expr()->eq('s.active', 1));
        }

        return $qb;
    }

    public function getSeoSettings()
    {
        $qb = $this->createQueryBuilder('s');
        $qb
            ->leftJoin('s.translations', 't')
            ->where($qb->expr()->eq('t.locale', $qb->expr()->literal($this->request->getLocale())))
            ->andWhere($qb->expr()->in('s.type', [
                'seo-title-base', 'seo-description-base', 'seo-keywords-base'
            ]))
            ->groupBy('s.id')
            ->addGroupBy('s.type');

        $query = $qb->getQuery();

        if ($this->container->get('app.helper')->isFront()) {
            $query->useQueryCache(true)
                ->useResultCache(true)
                ->setResultCacheId('seo_settings_' . self::getClassName());
        }

        $seoSettings = $query->getResult();
        $tmp = [];

        foreach ($seoSettings as $seoSetting) {
            $tmp[$seoSetting->getType()] = $seoSetting;
        }
        $seoSettings = $tmp;

        return $seoSettings;
    }

    public function getSetting($key)
    {

        return $this->container->get('app.content_repository')->getContentByType($key);
    }
}
