<?php

namespace Systeo\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SysteoProductBundle:Default:index.html.twig');
    }
}
