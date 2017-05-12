<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Offer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;


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
     * @Method("GET")
     */
    public function showCategoryAction($id)
    {
        return $this->render('user_offer/category_list_offers.html.twig',
            array('id' => $id));
    }

    /**
     * @Route("/{id}", requirements={"id":"\d+"})
     * @param Offer $offer
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     */
    public function showAction(Offer $offer)
    {
        if ($this->getUser()->getId() != $offer->getUser()->getid()) {
            throw $this->createNotFoundException("Offer not found");
        }
        $deleteForm = $this->createDeleteForm($offer);
        return $this->render('user_offer/show.html.twig', array(
            'offer' => $offer,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing offer entity.
     *
     * @Route("/{id}/edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Offer $offer)
    {
        if ($this->getUser()->getId() != $offer->getUser()->getid()) {
            throw $this->createNotFoundException("Offer not found");
        }

        $deleteForm = $this->createDeleteForm($offer);
        $editForm = $this->createForm('AppBundle\Form\OfferType', $offer);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_useroffer_show', array('id' => $offer->getId()));
        }

        return $this->render('user_offer/edit.html.twig', array(
            'offer' => $offer,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a offer entity.
     *
     * @Route("/{id}")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Offer $offer)
    {
        if ($this->getUser()->getId() != $offer->getUser()->getid()) {
            throw $this->createNotFoundException("Offer not found");
        }

        $form = $this->createDeleteForm($offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($offer);
            $em->flush();
        }

        return $this->redirectToRoute('app_useroffer_index');
    }

    /**
     * Creates a new offer entity.
     *
     * @Route("/new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        $user = $this->getUser();
        $offer = new Offer();
        $form = $this->createForm('AppBundle\Form\OfferType', $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $offer->setUser($user);
            $em->persist($offer);
            $em->flush();

            return $this->redirectToRoute('app_useroffer_show', array('id' => $offer->getId()));
        }

        return $this->render('user_offer/new.html.twig', array(
            'offer' => $offer,
            'form' => $form->createView(),
        ));
    }

    private function createDeleteForm(Offer $offer)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_useroffer_delete', array('id' => $offer->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
