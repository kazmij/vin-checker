<?php

namespace App\AdminBundle\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FM\ElfinderBundle\Event\ElFinderEvents;
use FM\ElfinderBundle\Event\ElFinderPreExecutionEvent;
use FM\ElfinderBundle\Event\ElFinderPostExecutionEvent;
use FM\ElfinderBundle\Controller\ElFinderController as MainElFinderController;

class ElFinderController extends MainElFinderController
{

    /**
     * Renders Elfinder.
     *
     * @param Request $request
     * @param string  $instance
     * @param string  $homeFolder
     *
     * @return Response
     */
    public function showAction(Request $request, $instance, $homeFolder)
    {
        return parent::showAction($request, $instance, $homeFolder);
    }
}
