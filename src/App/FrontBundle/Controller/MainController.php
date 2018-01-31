<?php

namespace App\FrontBundle\Controller;

use Intervention\Image\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{

    protected $viewData = [];

    public function init()
    {

    }

    public function getPetHelper()
    {
        return $this->container->get('app.pet');
    }

    public function getUploader()
    {
        return $this->container->get('app.uploader');
    }

}
