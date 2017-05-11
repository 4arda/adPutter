<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Offer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * Class UserOfferController
 * @package AppBundle\Controller
 * @Route("/my/offers")
 * @Security("has_role('ROLE_USER')")
 */
class UserOfferController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('user_offer/list_offers.html.twig');
    }

    /**
     * @Route("/category/{id}")
     */
    public function showCategoryAction($id)
    {
        return $this->render('user_offer/category_list_offers.html.twig',
            array('id' => $id));
    }

    /**
     * @Route("/{id}")
     * @param Offer $offer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Offer $offer)
    {
        $deleteForm = $this->createDeleteForm($offer);
        return $this->render('user_offer/show.html.twig', array(
            'offer' => $offer,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    private function createDeleteForm(Offer $offer)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('offer_delete', array('id' => $offer->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
