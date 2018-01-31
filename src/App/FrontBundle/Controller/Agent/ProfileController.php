<?php

namespace App\FrontBundle\Controller\Agent;

use App\AdminBundle\Entity\Newsletter;
use App\FrontBundle\Model\Contact;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\FrontBundle\Controller\MainController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\AdminBundle\Entity\Portal;
use App\AdminBundle\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ProfileController extends MainController
{

    public function indexAction(Request $request)
    {
        $response = $this->render('AppFrontBundle:Agent/Profile:index.html.twig', []);

        return $response;
    }

    public function editAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $editForm = $this->createForm('App\FrontBundle\Form\AgentType', $user, [
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

            $this->addFlash('success', sprintf('Twoje konto zostalo zaktualizowane'));
            if($passChanged) {
                $this->addFlash('success', sprintf('Haslo do konta zostalo zmienione'));
            }

            return $this->redirectToRoute('app_user_agent_profile');
        }

        return $this->render('AppFrontBundle:Agent/Profile:edit.html.twig', array(
            'edit_form' => $editForm->createView()
        ));
    }
}
