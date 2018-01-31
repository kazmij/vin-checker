<?php

namespace App\AdminBundle\Repository;

use App\AdminBundle\Entity\Page;
use App\AdminBundle\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use App\AdminBundle\Entity\Portal;
use Symfony\Component\DependencyInjection\Container;

/**
 * PageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PageRepository extends ContentRepository
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
        $qb = $this->createQueryBuilder('p');
        $qb
            ->leftJoin('p.translations', 't')
            ->where($qb->expr()->eq('t.locale', $qb->expr()->literal($request->getLocale())));

        if($request->get('query')) {
            $searchArr = explode(' ', trim($request->get('query')));
            $conditions = [];

            foreach ($searchArr as $search) {
                if(strlen($search) >= 3) {
                    $conditions[] = $qb->expr()->like('t.name', $qb->expr()->literal('%' . $search . '%'));
                    $conditions[] = $qb->expr()->like('t.description', $qb->expr()->literal('%' . $search . '%'));
                }
            }

            if(!empty($conditions)) {
                $qb->andWhere(implode(' OR ', $conditions));
            }
        }

        if($request->get('from')) {
            $date = \DateTime::createFromFormat('Y-m-d H:i', $request->get('from'));
            if($date) {
                $qb->andWhere($qb->expr()->gte('p.createdAt', $qb->expr()->literal($date->format('Y-m-d H:i'))));
            }
        }

        if($request->get('to')) {
            $date = \DateTime::createFromFormat('Y-m-d H:i', $request->get('to'));
            if($date) {
                $qb->andWhere($qb->expr()->lte('p.createdAt', $qb->expr()->literal($date->format('Y-m-d H:i'))));
            }
        }

        if($request->get('active')) {
            $qb->andWhere($qb->expr()->eq('p.active', 1));
        }

        return $qb;
    }
}
