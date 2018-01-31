<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Category controller.
 *
 */
class CategoryController extends Controller
{
    /**
     * Lists all category entities.
     *
     */
    public function indexAction(Request $request)
    {
        $categories = $this->get('app.category_repository')->getJsTree($request->get('type'), null, true);

        return $this->render('AppAdminBundle:Category:index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Creates a new category entity.
     *
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm('App\AdminBundle\Form\CategoryType', $category, ['type' => $request->get('type')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setType($request->get('type'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_edit', array('id' => $category->getId(), 'type' => $request->get('type')));
        }

        return $this->render('AppAdminBundle:Category:new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing category entity.
     *
     */
    public function editAction(Request $request, Category $category)
    {
        $editForm = $this->createForm('App\AdminBundle\Form\CategoryType', $category, ['type' => $request->get('type')]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_edit', array('id' => $category->getId(), 'type' => $request->get('type')));
        }

        return $this->render('AppAdminBundle:Category:edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a category entity.
     *
     */
    public function deleteAction(Request $request, Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('category_index', ['type' => $request->get('type')]);
    }
}
