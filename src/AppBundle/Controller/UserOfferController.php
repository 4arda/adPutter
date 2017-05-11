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
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $offers = $em->getRepository("AppBundle:Offer")->findBy(['user' => $user->getId()]);
        return $this->render('user_offer/list_offers.html.twig', array('offers' => $offers));
    }

    /**
     * @Route("/category/{id}/my/offers/")
     */
    public function showCategoryOffersAction($id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $offers = $em->getRepository("AppBundle:Offer")->findBy(['user' => $user->getId()]);
        return $this->render('user_offer/category_list_offers.html.twig',
            array('offers' => $offers, 'id' => $id));
    }



    public function getUser()
    {
        return parent::getUser();
    }
}
