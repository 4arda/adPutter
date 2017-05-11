<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserOfferController extends Controller
{
    /**
     * @Route("/my/offers")
     */
    public function indexAction()
    {
        return $this->render('user_offer/list_offers.html.twig');
    }

    /**
     * @Route("/my/offers/category/{id}")
     */
    public function showCategoryAction($id)
    {
        return $this->render('user_offer/category_list_offers.html.twig',
            array('id' => $id));
    }



    public function getUser()
    {
        return parent::getUser();
    }
}
