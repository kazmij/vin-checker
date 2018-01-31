<?php

namespace App\FrontBundle\Controller\Agent;

use App\AdminBundle\Entity\Car;
use App\AdminBundle\Entity\CarAccidentHistory;
use App\AdminBundle\Entity\CarMileageHistory;
use App\AdminBundle\Entity\CarPhoto;
use App\FrontBundle\Model\Contact;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\FrontBundle\Controller\MainController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\AdminBundle\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CarAccidentController extends MainController
{

    public function indexAction(Request $request, Car $car)
    {

        $request->attributes->set('carId', $car->getId());
        $repository = $this->get('app.car_accident_repository');
        $pagination = $this->get('app.paginator')->getPagination($repository);

        return $this->render('AppFrontBundle:Agent/CarAccident:index.html.twig', array(
            'pagination' => $pagination,
            'car' => $car
        ));
    }

    public function newAction(Request $request, Car $car)
    {
        $carAccident = new CarAccidentHistory();
        $photo = new CarPhoto();
        $carAccident->addPhoto($photo);
        $mileage = new CarMileageHistory();
        $carAccident->addMileagesHistory($mileage);
        $carAccident->setCar($car);
        $form = $this->createForm('App\FrontBundle\Form\CarAccidentType', $carAccident);

        if ($this->saveData($form)) {
            return $this->redirectToRoute('app_user_agent_cars_accident', ['id' => $car->getId()]);
        }

        return $this->render('AppFrontBundle:Agent/CarAccident:new.html.twig', array(
            'form' => $form->createView(),
            'car' => $car
        ));
    }

    public function editAction(Request $request, CarAccidentHistory $carAccidentHistory)
    {
        $car = $carAccidentHistory->getCar();

        if(!count($carAccidentHistory->getPhotos())) {
            $photo = new CarPhoto();
            $carAccidentHistory->addPhoto($photo);
        }
        if(!count($carAccidentHistory->getMileagesHistory())) {
            $mileage = new CarMileageHistory();
            $carAccidentHistory->addMileagesHistory($mileage);
        }
        $form = $this->createForm('App\FrontBundle\Form\CarAccidentType', $carAccidentHistory);

        if ($this->saveData($form)) {
            return $this->redirectToRoute('app_user_agent_cars_accident', ['id' => $car->getId()]);
        }

        return $this->render('AppFrontBundle:Agent/CarAccident:edit.html.twig', array(
            'form' => $form->createView(),
            'car' => $car
        ));
    }

    /**
     * Remove a page entity.
     */
    public function removeAction(Request $request, CarAccidentHistory $carAccidentHistory)
    {
        $car = $carAccidentHistory->getCar();
        $em = $this->getDoctrine()->getManager();
        $this->get('app.helper')->disableSoftDeletable($em);
        $em->remove($carAccidentHistory);

        $this->addFlash('success', 'Zdarzednie dla pojazdu zostało usunięte!');
        $em->flush();

        return $this->redirectToRoute('app_user_agent_cars_accident', ['id' => $car->getId()]);
    }

    private function saveData(Form $form)
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var $carAccident CarAccidentHistory */
            $carAccident = $form->getData();
            $car = $carAccident->getCar();
            $data = $request->get($form->getName());
            if (isset($data['photosToRemove'])) {
                $photosToRemove = explode(',', $data['photosToRemove']);
                $this->get('app.helper')->removePhotos($photosToRemove);
            }

            if (count($carAccident->getPhotos())) {
                foreach ($carAccident->getPhotos() as $photo) {
                    /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $fileUpload */
                    $fileUpload = $photo->getFileUpload();
                    if ($fileUpload) {
                        $fileName = md5(uniqid()) . '.' . $fileUpload->guessExtension();
                        $fileUpload->move($this->get('kernel')->getUploadDir(), $fileName);
                        $photo->setPath($fileName);
                    }
                }
            }

            if(count($carAccident->getMileagesHistory())) {

                foreach ($carAccident->getMileagesHistory() as $mileage) {
                    $car->addMileagesHistory($mileage);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($carAccident);
            $em->flush();

            return true;
        }

        return false;
    }
}
