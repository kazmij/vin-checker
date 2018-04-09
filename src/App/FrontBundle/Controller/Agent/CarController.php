<?php

namespace App\FrontBundle\Controller\Agent;

use App\AdminBundle\Entity\Car;
use App\AdminBundle\Entity\CarPhoto;
use Symfony\Component\Form\Form;
use App\FrontBundle\Controller\MainController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CarController extends MainController
{

    public function indexAction(Request $request)
    {
        $repository = $this->get('app.car_repository');
        $pagination = $this->get('app.paginator')->getPagination($repository);

        return $this->render('AppFrontBundle:Agent/Car:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    public function newAction(Request $request)
    {
        $car = new Car();
        $photo = new CarPhoto();
        $car->addPhoto($photo);
        $form = $this->createForm('App\FrontBundle\Form\CarType', $car, [
            'manufacturers' => $this->get('app.manufacturer_repository')->getChoices()
        ]);

        if ($this->saveData($form)) {
            $this->addFlash('success', 'Pojazd został poprawnie zapisany!');
            return $this->redirectToRoute('app_user_agent_cars');
        }

        return $this->render('AppFrontBundle:Agent/Car:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, Car $car)
    {
        $loggedUser = $this->getUser();

        if($car->getUser() && $car->getUser()->getId() != $loggedUser->getId()) {
            $this->addFlash('error', 'Ten pojazd zostałt dodany przez innego agenta i nie może być przez Ciebie edytowany!');

            return $this->redirectToRoute('app_user_agent_cars');
        }

        if(!count($car->getPhotos())) {
            $photo = new CarPhoto();
            $car->addPhoto($photo);
        }
        $form = $this->createForm('App\FrontBundle\Form\CarType', $car, [
            'manufacturers' => $this->get('app.manufacturer_repository')->getChoices(),
            'models' => $this->get('app.model_repository')->getChoices($car->getManufacturerData()),
            'trims' => $this->get('app.model_trim_repository')->getChoices($car->getModelData())
        ]);

        if ($this->saveData($form)) {
            $this->addFlash('success', 'Pojazd został poprawnie zaktualizowany!');

            return $this->redirectToRoute('app_user_agent_cars');
        }

        return $this->render('AppFrontBundle:Agent/Car:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Remove a page entity.
     */
    public function removeAction(Request $request, Car $car)
    {
        $loggedUser = $this->getUser();

        if($car->getUser() && $car->getUser()->getId() != $loggedUser->getId()) {
            $this->addFlash('error', 'Ten pojazd zostałt dodany przez innego agenta i nie może być przez Ciebie edytowany!');

            return $this->redirectToRoute('app_user_agent_cars');
        }

        $em = $this->getDoctrine()->getManager();
        $this->get('app.helper')->disableSoftDeletable($em);
        $em->remove($car);

        $this->addFlash('success', 'Pojazd został usunięty!');
        $em->flush();

        return $this->redirectToRoute('app_user_agent_cars');
    }

    private function saveData(Form $form)
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var $car Car */
            $car = $form->getData();
            $data = $request->get($form->getName());
            if (isset($data['trimData'])) {
                $trim = $this->get('app.model_trim_repository')->find($data['trimData']);
                if ($trim) {
                    $car->setModelTrim($trim);
                }
            }
            if (isset($data['modelData'])) {
                $model = $this->get('app.model_repository')->find($data['modelData']);
                if ($model) {
                    $car->setModel($model);
                }
            }
            if (isset($data['manufacturerData'])) {
                $manufacturer = $this->get('app.manufacturer_repository')->find($data['manufacturerData']);
                if ($manufacturer) {
                    $car->setManufacturer($manufacturer);
                }
            }

            if (isset($data['photosToRemove'])) {
                $photosToRemove = explode(',', $data['photosToRemove']);
                $this->get('app.helper')->removePhotos($photosToRemove);
            }

            if (count($car->getPhotos())) {
                foreach ($car->getPhotos() as $photo) {
                    /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $fileUpload */
                    $fileUpload = $photo->getFileUpload();
                    if ($fileUpload) {
                        $fileName = md5(uniqid()) . '.' . $fileUpload->guessExtension();
                        $fileUpload->move($this->get('kernel')->getUploadDir(), $fileName);
                        $photo->setPath($fileName);
                    }
                }
            }

            $car->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($car);
            $em->flush();

            return true;
        }

        return false;
    }

    public function loadModelsAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $data = $this->get('app.model_repository')->getChoices();

            return new JsonResponse([
                'success' => true,
                'models' => $data
            ]);
        }

        exit('Only AJAX!');
    }

    public function loadTrimsAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $data = $this->get('app.model_trim_repository')->getChoices();

            return new JsonResponse([
                'success' => true,
                'trims' => $data
            ]);
        }

        exit('Only AJAX!');
    }
}
