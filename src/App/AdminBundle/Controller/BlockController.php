<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Entity\Block;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Cocur\Slugify\Slugify;

/**
 * BlockController.
 */
class BlockController extends Controller
{
    /**
     * Lists all page entities.
     *
     */
    public function indexAction()
    {
        $repository = $this->get('app.block_repository');
        $pagination = $this->get('app.paginator')->getPagination($repository);

        return $this->render('AppAdminBundle:Block:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new page entity.
     *
     */
    public function newAction(Request $request)
    {
        $block = new Block();
        $form = $this->createForm('App\AdminBundle\Form\BlockType', $block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if($block->getType()) {
                $slugify = new Slugify();
                $block->setType($slugify->slugify($block->getType()));
            }
            $em->persist($block);
            $em->flush();

            return $this->redirectToRoute('block_edit', array('id' => $block->getId()));
        }

        return $this->render('AppAdminBundle:Block:new.html.twig', array(
            'block' => $block,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing block entity.
     *
     */
    public function editAction(Request $request, Block $block)
    {
        $editForm = $this->createForm('App\AdminBundle\Form\BlockType', $block);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if($block->getType()) {
                $slugify = new Slugify();
                $block->setType($slugify->slugify($block->getType()));
            }
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('block_edit', array('id' => $block->getId()));
        }

        return $this->render('AppAdminBundle:Block:edit.html.twig', array(
            'block' => $block,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a block entity.
     *
     */
    public function deleteAction(Request $request, Block $block)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($block);
        $em->flush();

        return $this->redirectToRoute('block_index');
    }
}
