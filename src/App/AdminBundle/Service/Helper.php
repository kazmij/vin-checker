<?php

namespace App\AdminBundle\Service;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Form;

class Helper
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


    public function isAdmin()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();

        return strpos($request->getUri(), 'admin');
    }

    public function isFront()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();

        return !strpos($request->getUri(), 'admin');
    }

    /**
     * Get all errors from doctrine form in readability figure
     *
     * @param Form $form - form object
     * @param bool $returnString - if the true then string will be returned otherwise errors array
     * @return array|string
     */
    public function getAllFormErrors(Form $form, $returnString = true)
    {
        $retval = array();
        $errors = $form->getErrors(true, true);
        if ($errors) {
            foreach ($errors as $key => $error) {
                if ($error->getMessage()) {
                    if ($returnString) {
                        $retval[$error->getOrigin()->getName()] = $error->getOrigin()->getName().': '.$error->getMessage();
                    } else {
                        $retval[$error->getOrigin()->getName()] = $error->getMessage();
                    }
                }
            }
        }

        if ($returnString) {
            return implode("\n", $retval);
        } else {
            return $retval;
        }
    }

    public function disableSoftDeletable($em){
        // cycle through all registered event listeners
        foreach ($em->getEventManager()->getListeners() as $eventName => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof \Knp\DoctrineBehaviors\ORM\SoftDeletable\SoftDeletableSubscriber) {

                    // store the event listener, that gets removed
                    $originalEventListeners[$eventName] = $listener;

                    // remove the SoftDeletableSubscriber event listener
                    $em->getEventManager()->removeEventListener($eventName, $listener);
                }
            }
        }
    }

    public function removePhotos($ids) {
        if(!is_array($ids)) {
            $ids = [$ids];
        }
        if($ids) {
            $photosRepo = $this->container->get('app.car_photo_repository');
            $em = $this->container->get('doctrine')->getManager();
            $this->container->get('app.helper')->disableSoftDeletable($em);
            $qb = $photosRepo->createQueryBuilder('p');
            $photos = $qb->where($qb->expr()->in('p.id', $ids))
                ->getQuery()
                ->getResult();

            $uploadPath = $this->container->get('kernel')->getUploadDir();
            foreach ($photos as $photo) {
                @unlink($uploadPath . $photo->getPath());
                $photo->setCar(null);
                $em->remove($photo);
                $em->flush();
            }
        }
    }
}