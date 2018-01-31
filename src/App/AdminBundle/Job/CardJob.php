<?php

namespace App\AdminBundle\Job;

use App\AdminBundle\Entity\Pet;
use App\FrontBundle\Service\PhantomService;
use Doctrine\ORM\EntityManager;
use IdeasBucket\QueueBundle\QueueableInterface;
use IdeasBucket\QueueBundle\Job\JobsInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class CardJob
 *
 * @package App\AdminBundle\Job
 */
class CardJob implements QueueableInterface
{

    /**
     * @var PhantomService
     */
    private $phantomService;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Container
     */
    private $container;

    /**
     * The number of max times the job may be attempted.
     *
     * @var int
     */
    public $maxTries = 3;

    /**
     * The number of max times the job may be attempted.
     *
     * @var int
     */
    private $timeout = 30;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->phantomService = $container->get('app.phantom');
        $this->em = $container->get('doctrine')->getManager();
    }

    /**
     * This job can be queued like this (assuming that you are inside the controller).
     *
     * $this->get('idb_queue')->push('appadminbundle.job.cardjob', $data);
     *
     * OR If you want to use specific queue and connection then.
     *
     * $this->get('idb_queue')->push('appadminbundle.job.cardjob', $data, 'some_queue', 'database');
     *
     * @param JobsInterface $job
     * @param mixed $data
     */
    public function fire(JobsInterface $job, $data = null)
    {
        /* @var $pet Pet */
        $pet = $this->container->get('app.pet_repository')->find($data['id']);

        $captured = $this->phantomService->captureImage($data['url'], realpath($this->container->getParameter('petUploadsDirectory')) . DIRECTORY_SEPARATOR . $pet->getHash());

        if($captured) {
            $this->container->get('app.uploader')->removeS3Objects($pet->getPetImage());
            $pet
                ->setPetCard($pet->getHash() . '.png')
                ->setPetCardSmaller($pet->getHash() . '360x495.png')
                ->setPetCardTwitter($pet->getHash() . '1080x540.jpg')
                ->setPetCardInstagram($pet->getHash() . '1080x1080.jpg')
                ->setPetCardFb($pet->getHash() . '1200x1650.png')
                ->setGenerated(true);

            $this->em->flush();
        }

        $job->delete(); // If you want to put job back into the queue then $job->release();
    }
}
