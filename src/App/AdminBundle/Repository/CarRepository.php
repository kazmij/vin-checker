<?php

namespace App\AdminBundle\Repository;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

/**
 * CarRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CarRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var Request
     */
    private $request;

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
        $qb = $this->createQueryBuilder('c');
        $qb
            ->leftJoin('c.modelTrim', 'mt')
            ->leftJoin('mt.model', 'md')
            ->leftJoin('md.manufacturer', 'm')
            ->leftJoin('c.mileagesHistory', 'cm')
            ->leftJoin('c.accidentsHistory', 'ah');

        if ($request->get('query')) {
            $searchArr = explode(' ', trim($request->get('query')));
            $conditions = [];

            foreach ($searchArr as $search) {
                if (strlen($search) >= 3) {
                    $conditions[] = $qb->expr()->like('t.name', $qb->expr()->literal('%' . $search . '%'));
                }
            }

            if (!empty($conditions)) {
                $qb->andWhere(implode(' OR ', $conditions));
            }
        }

        if ($request->get('active')) {
            $qb->andWhere($qb->expr()->eq('b.active', 1));
        }

        if (!$request->get('sort')) {
            $qb->orderBy('c.createdAt', 'DESC');
        }

        $qb->addGroupBy('c.id');


        return $qb;
    }
}
