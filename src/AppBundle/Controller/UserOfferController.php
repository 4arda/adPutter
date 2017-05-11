<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserOfferController extends Controller
{
    public function listUserOffersAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $userOffers = $em->getRepository("AppBundle:Offer")->findBy(['user' => $user->getId()]);
        return $this->render('offer/index.html.twig', array('offers' => $userOffers));
    }

    /**
     * @Route("/my/offers")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $userOffers = $em->getRepository("AppBundle:Offer")->findBy(['user' => $user->getId()]);
        return $this->render('user_offer/list_offers.html.twig', array('offers' => $userOffers));
    }

    public function getUser()
    {
        return parent::getUser();
    }
}
