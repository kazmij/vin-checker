<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Entity\Setting;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Cocur\Slugify\Slugify;

/**
 * SettingController.
 *
 */
class SettingController extends Controller
{
    /**
     * Lists all page entities.
     *
     */
    public function indexAction()
    {
        $repository = $this->get('app.setting_repository');
        $pagination = $this->get('app.paginator')->getPagination($repository);

        return $this->render('AppAdminBundle:Setting:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new page entity.
     *
     */
    public function newAction(Request $request)
    {
        $setting = new Setting();
        $form = $this->createForm('App\AdminBundle\Form\SettingType', $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($setting->getType()) {
                $slugify = new Slugify();
                $setting->setType($slugify->slugify($setting->getType()));
            }
            $em->persist($setting);
            $em->flush();

            $this->addFlash('success', sprintf($this->get('translator')->trans("Ustawienie %s zostało zapisane."), $setting->translate()->getName()));

            return $this->redirectToRoute('settings_edit', array('id' => $setting->getId()));
        }

        return $this->render('AppAdminBundle:Setting:new.html.twig', array(
            'page' => $setting,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing page entity.
     *
     */
    public function editAction(Request $request, Setting $setting = null)
    {
        if (!$setting) {
            $this->addFlash('danger', sprintf($this->get('translator')->trans("Nie znaleziono ustawienia!")));

            return $this->redirectToRoute('settings_index');
        }

        $editForm = $this->createForm('App\AdminBundle\Form\SettingType', $setting);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($setting->getType()) {
                $slugify = new Slugify();
                $setting->setType($slugify->slugify($setting->getType()));
            }
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', sprintf($this->get('translator')->trans("Ustawienie %s zostało zaktualizowane"), $setting->translate()->getName()));

            return $this->redirectToRoute('settings_edit', array('id' => $setting->getId()));
        }

        return $this->render('AppAdminBundle:Setting:edit.html.twig', array(
            'page' => $setting,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a page entity.
     *
     */
    public function deleteAction(Request $request, Setting $setting = null)
    {
        if (!$setting) {
            $this->addFlash('danger', sprintf($this->get('translator')->trans("Nie znaleziono ustawienia!")));

            return $this->redirectToRoute('settings_index');
        }

        $em = $this->getDoctrine()->getManager();
        $name = $setting->translate()->getName();
        $em->remove($setting);

        $this->addFlash('success', sprintf($this->get('translator')->trans("Ustawienie %s zostało usunięte"), $name));
        $em->flush();

        return $this->redirectToRoute('settings_index');
    }

    /**
     * Status change.
     *
     */
    public function statusAction(Request $request, Setting $setting = null)
    {

        if (!$setting) {
            $this->addFlash('danger', sprintf($this->get('translator')->trans("Nie znaleziono ustawienia!")));

            return $this->redirectToRoute('settings_index');
        }

        $em = $this->getDoctrine()->getManager();
        $setting->setActive(!$setting->getActive());
        $em->flush();

        return new JsonResponse([
            'success' => true
        ]);
    }
}
