<?php

namespace App\AdminBundle\Repository;

/**
 * ImageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ImageRepository extends \Doctrine\ORM\EntityRepository
{

    public function getObjectImages($objectId = null)
    {
        if($objectId) {
            $qb = $this->createQueryBuilder('i');
            return $qb->where($qb->expr()->eq('i.content', $objectId));
        } else {
            return null;
        }
    }
}
