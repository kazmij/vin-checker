<?php

namespace App\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\AdminBundle\Entity\Page;
use App\AdminBundle\Form\PageType;

class IndexController extends Controller
{

    public function indexAction()
    {

        return $this->redirect($this->generateUrl('page_index'));
    }


}
