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

        $offers = $em->getRepository('AppBundle:Offer')->findAll();

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
     * Creates a new offer entity.
     *
     * @Route("/new", name="offer_new")
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

            return $this->redirectToRoute('offer_show', array('id' => $offer->getId()));
        }

        return $this->render('offer/new.html.twig', array(
            'offer' => $offer,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a offer entity.
     *
     * @Route("/{id}", name="offer_show")
     * @Method("GET")
     */
    public function showUserAction(Offer $offer)
    {
        $deleteForm = $this->createDeleteForm($offer);
        return $this->render('offer/show.html.twig', array(
            'offer' => $offer,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing offer entity.
     *
     * @Route("/{id}/edit", name="offer_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Offer $offer)
    {
        $deleteForm = $this->createDeleteForm($offer);
        $editForm = $this->createForm('AppBundle\Form\OfferType', $offer);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('offer_edit', array('id' => $offer->getId()));
        }

        return $this->render('offer/edit.html.twig', array(
            'offer' => $offer,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a offer entity.
     *
     * @Route("/{id}", name="offer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Offer $offer)
    {
        $form = $this->createDeleteForm($offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($offer);
            $em->flush();
        }

        return $this->redirectToRoute('offer_index');
    }

    /**
     * Creates a form to delete a offer entity.
     *
     * @param Offer $offer The offer entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Offer $offer)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('offer_delete', array('id' => $offer->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function getUser()
    {
        return parent::getUser();
    }
}
