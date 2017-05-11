<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainPageController extends Controller
{
    /**
     * @param
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('mainPage/index.html.twig');
    }
}
