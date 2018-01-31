<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Entity\Banner;
use App\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Cocur\Slugify\Slugify;

/**
 * AgentController.
 *
 */
class AgentController extends Controller
{
    /**
     * Lists all page entities.
     *
     */
    public function indexAction(Request $request)
    {
        $request->attributes->set('role', 'ROLE_AGENT');
        $repository = $this->get('app.user_repository');
        $pagination = $this->get('app.paginator')->getPagination($repository);

        return $this->render('AppAdminBundle:Agent:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new page entity.
     *
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('App\AdminBundle\Form\AgentType', $user, [
            //'types' => $allowedTypes
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('fos_user.util.canonical_fields_updater')->updateCanonicalFields($user);
            $plainPassword = $user->getPlainPassword();
            $this->get('fos_user.util.password_updater')->hashPassword($user);
            $em = $this->get('doctrine')->getManager();
            $user->addRole('ROLE_AGENT');
            $em->persist($user);
            $em->flush($user);

            $html = $this->renderView('AppAdminBundle:Agent/emails:created.html.twig', [
                'user' => $user,
                'password' => $plainPassword
            ]);

            $this->get('app.mailer')->sendMail($user->getEmail(), 'Twoje konto agenta zostalo utworzone!', $html);

            $this->addFlash('success', sprintf('Nowy agent %s zostal zapisany', $user->getUsername()));

            return $this->redirectToRoute('agents_index');
        }

        return $this->render('AppAdminBundle:Agent:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing page entity.
     *
     */
    public function editAction(Request $request, User $user = null)
    {
        if (!$user) {
            $this->addFlash('danger', 'Nie ma takiego agenta!');

            return $this->redirectToRoute('agents_index');
        }

        $editForm = $this->createForm('App\AdminBundle\Form\AgentType', $user, [
            //'types' => $allowedTypes
        ]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->get('doctrine')->getManager();
            $passChanged = false;
            $this->get('fos_user.util.canonical_fields_updater')->updateCanonicalFields($user);
            if ($user->getPlainPassword()) {
                $this->get('fos_user.util.password_updater')->hashPassword($user);
                $em = $this->get('doctrine')->getManager();
                $passChanged = true;
            }
            $em->persist($user);
            $em->flush($user);

            $this->addFlash('success', sprintf('Agent %s zostal zaktualizowany', $user->getUsername()));
            if($passChanged) {
                $this->addFlash('success', sprintf('Haslo agenta %s zostalo zmienione', $user->getUsername()));
            }

            return $this->redirectToRoute('agents_index');
        }

        return $this->render('AppAdminBundle:Agent:edit.html.twig', array(
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a page entity.
     *
     */
    public function deleteAction(Request $request, User $user = null)
    {
        if (!$user) {
            $this->addFlash('danger', 'Nie ma takiego agenta!');

            return $this->redirectToRoute('agents_index');
        }

        $em = $this->getDoctrine()->getManager();
        $name = $user->getUsername();
        $em->remove($user);

        $this->addFlash('success', sprintf('Agent %s zostal usuniety', $name));
        $em->flush();

        return $this->redirectToRoute('agents_index');
    }

    /**
     * Status change.
     *
     */
    public function statusAction(Request $request, User $user = null)
    {

        if (!$user) {
            $this->addFlash('danger', 'Nie ma takiego agenta!');

            return $this->redirectToRoute('agents_index');
        }

        $em = $this->getDoctrine()->getManager();
        $user->setEnabled(!$user->isEnabled());
        $em->flush();

        return new JsonResponse([
            'success' => true
        ]);
    }
}
