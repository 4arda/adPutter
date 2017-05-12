<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Offer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Offer controller.
 *
 * @Route("offer")
 */
class OfferController extends Controller
{
    /**
     * Lists all offer entities.
     *
     * @Route("/", name="offer_index")
     * @Method("GET")
     */
    public function listAllAction()
    {
        $em = $this->getDoctrine()->getManager();

        $offers = $em->getRepository('AppBundle:Offer')->findAllActual();

        return $this->render(':offer:_list_all.html.twig', array(
            'offers' => $offers,
        ));
    }

    public function listInCategoryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $offers = $em->getRepository('AppBundle:Offer')->findAllByCategoryId($id);
        return $this->render(':offer:_list_all.html.twig', array('offers' => $offers));
    }

    public function listUserAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $offers = $em->getRepository("AppBundle:Offer")->findBy(['user' => $user->getId()]);
        return $this->render(':offer:_list_all_user.html.twig', array('offers' => $offers));
    }

    public function listUserByCategoryAction($id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $offers = $em->getRepository('AppBundle:Offer')->findAllByUserAndCategoryId($id, $user->getId());
        return $this->render(':offer:_list_all_user.html.twig', array('offers' => $offers));
    }

    /**
     * Finds and displays a offer entity.
     *
     * @Route("/{id}", name="offer_show")
     * @Method("GET")
     */
    public function showAction(Offer $offer)
    {
        return $this->render('offer/show.html.twig', array(
            'offer' => $offer,
        ));
    }
}
