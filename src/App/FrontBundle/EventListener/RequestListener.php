<?php

namespace App\FrontBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\DependencyInjection\Container;

class RequestListener
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

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if (method_exists($controller[0], 'init')) {
            $controller[0]->init();
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if($this->container->get('app.helper')->isAdmin()) {
            $response = $event->getResponse();
            $response->headers->set('Cache-Control', "private, no-cache, no-store");
        }
    }
}