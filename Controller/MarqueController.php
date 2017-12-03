<?php

namespace Systeo\ProductBundle\Controller;

use Systeo\ProductBundle\Entity\Marque;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Marque controller.
 *
 * @Route("marque")
 */
class MarqueController extends Controller {

    /**
     * Lists all marque entities.
     *
     * @Route("/", name="marque_index")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request) {
        

        if (false) {
            $url = $this->buildSearchUrl($request->request->all());

            if (!empty($url)) {
                return $this->redirectToRoute('marque_index', $url);
            }
        }
        $em = $this->getDoctrine()->getManager();

        $paginator = $this->get('knp_paginator');

        $marques = $paginator->paginate(
                $em->getRepository('SysteoProductBundle:Marque')->MyFindAll($request->query->all()), /* query NOT result */ $request->query->getInt('page', 1)/* page number */, 10/* limit per page */
        );
        return $this->render('SysteoProductBundle:marque:index.html.twig', array(
                    'marques' => $marques,
                    'parametre' => $this->parametreUrl($request->query)
        ));
    }

    /**
     * Creates a new marque entity.
     *
     * @Route("/new", name="marque_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request) {
        $marque = new Marque();
        $form = $this->createForm('Systeo\ProductBundle\Form\MarqueType', $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($marque);
            $em->flush($marque);
            $this->addFlash('success', 'Nouvelle marque ajoutée avec succès.');
            return $this->redirectToRoute('marque_index', array('id' => $marque->getId()));
        }

        return $this->render('SysteoProductBundle:marque:new.html.twig', array(
                    'marque' => $marque,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a marque entity.
     *
     * @Route("/{id}", name="marque_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(Marque $marque) {
        $deleteForm = $this->createDeleteForm($marque);

        return $this->render('SysteoProductBundle:marque:show.html.twig', array(
                    'marque' => $marque,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing marque entity.
     *
     * @Route("/{id}/edit", name="marque_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Marque $marque) {
        $deleteForm = $this->createDeleteForm($marque);
        $editForm = $this->createForm('Systeo\ProductBundle\Form\MarqueType', $marque);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Marque modifiée avec succès.');
            return $this->redirectToRoute('marque_edit', array('id' => $marque->getId()));
        }

        return $this->render('SysteoProductBundle:marque:edit.html.twig', array(
                    'marque' => $marque,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a marque entity.
     *
     * @Route("/{id}", name="marque_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, Marque $marque) {
        $form = $this->createDeleteForm($marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($marque);
            $em->flush($marque);
        }
        $this->addFlash('success', 'Marque supprimée avec succès.');
        return $this->redirectToRoute('marque_index');
    }

    /**
     * Creates a form to delete a marque entity.
     *
     * @param Marque $marque The marque entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Marque $marque) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('marque_delete', array('id' => $marque->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * 
     * @param array $data $request->query->all()
     * @return array URL parameters
     */
    private function buildSearchUrl($data) {
        $url = [];
        foreach ($data as $k => $v) {
            if (isset($data['commercial_produitbundle_marque']['name']) && !empty($data['commercial_produitbundle_marque']['name'])) {
                $url['name'] = $data['commercial_produitbundle_marque']['name'];
            }
        }

        return $url;
    }

    /**
     * 
     * @param array $paramUrl
     * @return array
     */
    private function parametreUrl($paramUrl) {
        $parametre = [];
        $parametre["name"] = $paramUrl->get('name');
        return $parametre;
    }

}
