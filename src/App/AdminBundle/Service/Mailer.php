<?php

namespace App\AdminBundle\Service;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Form;

class Mailer
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

    public function sendMail($to, $subject, $body)
    {
        $message = new \Swift_Message($subject);
        $message->setFrom($this->container->getParameter('mailer_email'), $this->container->getParameter('mailer_email'))
            ->setTo($to)
            ->setBody($body, 'text/html');
        $mailer = $this->container->get('mailer');
        $mailer->send($message);

        return true;
    }

}