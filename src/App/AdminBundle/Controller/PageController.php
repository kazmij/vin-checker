<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Cocur\Slugify\Slugify;

/**
 * Page controller.
 *
 */
class PageController extends Controller
{
    /**
     * Lists all page entities.
     *
     */
    public function indexAction()
    {
        $repository = $this->get('app.page_repository');
        $pagination = $this->get('app.paginator')->getPagination($repository);

        return $this->render('AppAdminBundle:Page:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new page entity.
     *
     */
    public function newAction(Request $request)
    {
        $page = new Page();
        $form = $this->createForm('App\AdminBundle\Form\PageType', $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($page->getType()) {
                $slugify = new Slugify();
                $page->setType($slugify->slugify($page->getType()));
            }
            $em->persist($page);
            $em->flush();

            $this->addFlash('success', sprintf($this->get('translator')->trans("Page %s has been successfully saved."), $page->translate()->getName()));

            return $this->redirectToRoute('page_edit', array('id' => $page->getId()));
        }

        return $this->render('AppAdminBundle:Page:new.html.twig', array(
            'page' => $page,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing page entity.
     *
     */
    public function editAction(Request $request, Page $page = null)
    {
        if (!$page) {
            $this->addFlash('danger', sprintf($this->get('translator')->trans("Page not found!")));

            return $this->redirectToRoute('page_index');
        }

        $editForm = $this->createForm('App\AdminBundle\Form\PageType', $page);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($page->getType()) {
                $slugify = new Slugify();
                $page->setType($slugify->slugify($page->getType()));
            }
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', sprintf($this->get('translator')->trans("Page %s has been successfully updated."), $page->translate()->getName()));

            return $this->redirectToRoute('page_edit', array('id' => $page->getId()));
        }

        return $this->render('AppAdminBundle:Page:edit.html.twig', array(
            'page' => $page,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a page entity.
     *
     */
    public function deleteAction(Request $request, Page $page = null)
    {
        if (!$page) {
            $this->addFlash('danger', sprintf($this->get('translator')->trans("Page not found!")));

            return $this->redirectToRoute('page_index');
        }

        $em = $this->getDoctrine()->getManager();
        $name = $page->translate()->getName();
        $em->remove($page);

        $this->addFlash('success', sprintf($this->get('translator')->trans("Page %s has been successfully removed."), $name));
        $em->flush();

        return $this->redirectToRoute('page_index');
    }

    /**
     * Status change.
     *
     */
    public function statusAction(Request $request, Page $page = null)
    {

        if (!$page) {
            $this->addFlash('danger', sprintf($this->get('translator')->trans("Page not found!")));

            return $this->redirectToRoute('page_index');
        }

        $em = $this->getDoctrine()->getManager();
        $page->setActive(!$page->getActive());
        $em->flush();

        return new JsonResponse([
            'success' => true
        ]);
    }
}
