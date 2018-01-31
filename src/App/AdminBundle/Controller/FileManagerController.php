<?php

namespace App\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FileManagerController extends Controller
{
    public function showAction()
    {
        return $this->render('AppAdminBundle:FileManager:show.html.twig', []);
    }

}
