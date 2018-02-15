<?php

namespace App\FrontBundle\Controller;

use App\AdminBundle\Entity\Newsletter;
use App\FrontBundle\Model\Contact;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\FrontBundle\Controller\MainController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\AdminBundle\Entity\Portal;
use App\AdminBundle\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DefaultController extends MainController
{

    public function indexAction(Request $request)
    {
        if ($request->get('slug')) {

            return $this->forward('AppFrontBundle:Default:page', [
                'slug' => $request->get('slug')
            ]);
        }

        $homePage = $this->get('app.content_repository')->getContentByType('home');

        $responseData = [
            'page' => $homePage
        ];

        if($request->get('vinNumber')) {
            $vinNumber = $request->get('vinNumber');
            $car = $this->get('app.car_repository')->findOneByVin($vinNumber);
            $responseData['car'] = $car;
        }

        $response = $this->render('AppFrontBundle:Default:index.html.twig', $responseData);

        return $response;
    }

    /**
     * @param Request $request
     * @param string $slug
     */
    public function contentAction($param1 = null, $param2 = null, $param3 = null, $param4 = null, $param5 = null)
    {
        $args = array_reverse(func_get_args());
        $slug = null;
        foreach ($args as $arg) {
            if ($arg) {
                $slug = $arg;
                break;
            }
        }

        $content = $this->get('app.content_repository')->getBySlug($slug);
        if (!$content) {
            throw new NotFoundException($this->get('translator')->trans('Content not found!'));
        }

        $reflect = new \ReflectionClass($content);
        $contentEntityClass = $reflect->getShortName();

        return $this->forward('AppFrontBundle:Default:' . strtolower($contentEntityClass), [
            'slug' => $slug,
            'portal' => $this->get('request')->get('portal')
        ]);
    }

    /**
     * @param Request $request
     */
    public function pageAction(Request $request)
    {
        $page = $this->get('app.content_repository')->getBySlug($request->get('slug'));
        $data = [];
        if (!$page) {
            throw new NotFoundException($this->get('translator')->trans('Page not found!'));
        }

        $data['page'] = $page;

        $response = $this->render('AppFrontBundle:Page:item.html.twig', $data);

        return $response;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function flashAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $html = $this->renderView('@AppFront/layout/flash.html.twig');

            return new JsonResponse([
                'success' => true,
                'html' => $html
            ]);
        }

        return $this->redirectToRoute('app_front_page');
    }
}
