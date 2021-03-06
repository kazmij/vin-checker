<?php

namespace App\AdminBundle\Repository;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;
use IdeasBucket\QueueBundle\Entity\DatabaseQueueEntityInterface as EntityInterface;
use IdeasBucket\QueueBundle\Repository\DatabaseQueueRepositoryInterface as RepositoryInterface;
use App\AdminBundle\Entity\Queue;

/**
 * Class QueueRepository
 *
 * @package App\AdminBundle\Entity
 */
class QueueRepository extends EntityRepository implements RepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function getCount($queue)
    {
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->select('count(q.id)');
        $query->from('AppAdminBundle:Queue','q');

        return (int) $query->getQuery()->getSingleScalarResult();
    }

    /**
     * @inheritDoc
     */
    public function createRecord(array $data)
    {
        /** @var EntityInterface $entity */
        $entity = new Queue;

        return $entity->setQueue($data['queue'])
                      ->setPayload($data['payload'])
                      ->setAttempts($data['attempts'])
                      ->setReservedAt($data['reserved_at'])
                      ->setAvailableAt($data['available_at'])
                      ->setCreatedAt($data['created_at']);
    }

    /**
     * @inheritDoc
     */
    public function delete(EntityInterface $entity)
    {
        $em = $this->getEntityManager();
        $entity = $em->merge($entity);
        $em->getConnection()->beginTransaction(); // suspend auto-commit

        try {

            $em->remove($entity);
            $em->flush();
            $em->getConnection()->commit();

        } catch (\Exception $e) {

            $em->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @inheritDoc
     */
    public function saveInBulk(array $entities)
    {
        $batchSize = 20;
        $i = 1;
        $em = $this->getEntityManager();

        foreach ($entities as $entity) {

            $em->persist($entity);

            if (($i % $batchSize) === 0) {

                $em->flush();
                $em->clear(); // Detaches all objects from Doctrine!
            }

            ++$i;
        }

        $em->flush();
        $em->clear();
    }

    /**
     * @inheritDoc
     */
    public function save(EntityInterface $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    /**
     * @inheritDoc
     */
    public function getNextAvailableJob($getQueue, $retryAfter)
    {
        $currentTime = new \DateTimeImmutable;
        $expiration = $currentTime->modify((int) ($retryAfter * -1) . ' seconds');

        $dql = 'SELECT q
                FROM AppAdminBundle:Queue q
                WHERE q.queue = :queue
                AND ((q.reservedAt IS NULL AND q.availableAt <= :now) OR (q.reservedAt <= :expiration))
                ORDER BY q.id ASC';

        $em = $this->getEntityManager();

        $em->getConnection()->beginTransaction();

        try {

            $query = $this->getEntityManager()
                          ->createQuery($dql)
                          ->setMaxResults(1)
                          ->useQueryCache(false)
                          ->useResultCache(false)
                          ->setParameters([
                              'now'        => $currentTime->getTimestamp(),
                              'queue'      => $getQueue,
                              'expiration' => $expiration->getTimestamp()
                          ])
                          ->setLockMode(LockMode::PESSIMISTIC_WRITE);

            $result = $query->getOneOrNullResult();

            if ($result !== null) {

                $result->touch();
                $em->persist($result);
                $em->flush();
            }

            $em->getConnection()->commit();

            return $result;

        } catch (\Exception $exception) {

            $em->getConnection()->rollBack();
        }
    }

    /**
     * @inheritDoc
     */
    public function findById($id)
    {
        return $this->getEntityManager()->find('AppAdminBundle:Queue', $id);
    }
}
