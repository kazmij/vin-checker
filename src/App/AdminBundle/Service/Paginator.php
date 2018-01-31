<?php

namespace App\AdminBundle\Service;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\Container;

class Paginator
{
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
    }

    /**
     * @param EntityRepository $repository
     * @return mixed
     * @throws \Exception
     */
    public function getPagination(EntityRepository $repository, $count = null)
    {

        if (method_exists($repository, 'getPaginationQueryBuilder')) {
            $request = $this->container->get('request_stack')->getCurrentRequest();
            $queryBuilder = $repository->getPaginationQueryBuilder($request);
            $paginationSettings = $this->container->hasParameter('pagination') ? $this->container->getParameter('pagination') : [];
            if($count) {
                $perPage = $count;
            } else {
                if (!empty($paginationSettings['perPage'])) {
                    $perPage = $paginationSettings['perPage'];
                } else {
                    $perPage = 15;
                }
            }

            $query = $queryBuilder->getQuery();
            if ($this->container->get('app.helper')->isFront()) {
                $query
                    ->useQueryCache(true)
                    ->useResultCache(true)
                    ->setResultCacheId('pagination_' . $repository->getClassName());
            }

            $paginator = $this->container->get('knp_paginator');
            $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), $perPage, [
                'wrap-queries' => true,
                'distinct' => false
            ]);

            return $pagination;
        } else {
            throw new \Exception('Repository method not found!');
        }
    }
}