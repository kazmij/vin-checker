<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Entity\Banner;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Cocur\Slugify\Slugify;

/**
 * BannerController.
 *
 */
class BannerController extends Controller
{
    /**
     * Lists all page entities.
     *
     */
    public function indexAction()
    {
        $repository = $this->get('app.banner_repository');
        $pagination = $this->get('app.paginator')->getPagination($repository);

        return $this->render('AppAdminBundle:Banner:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new page entity.
     *
     */
    public function newAction(Request $request)
    {
        $banner = new Banner();
        $form = $this->createForm('App\AdminBundle\Form\BannerType', $banner, [
            //'types' => $allowedTypes
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $fileMobileUpload */
            $fileMobileUpload = $banner->getFileMobileUpload();
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $fileDesktopUpload */
            $fileDesktopUpload = $banner->getFileDesktopUpload();
            $filePathMobile = md5(uniqid()) . '.' . $fileMobileUpload->guessExtension();
            $filePathDesktop = md5(uniqid()) . '.' . $fileDesktopUpload->guessExtension();
            $this->container->get('amazonS3.client')->putObject([
                'ACL' => 'public-read',
                'Bucket' => $this->container->getParameter('amazon_s3_bucket_name'),
                'Key' => $this->container->getParameter('amazon_s3_bucket_directory') . $filePathMobile,
                'Body' => file_get_contents($fileMobileUpload->getRealPath()),
                'ContentType' => 'image/png'
            ]);
            $this->container->get('amazonS3.client')->putObject([
                'ACL' => 'public-read',
                'Bucket' => $this->container->getParameter('amazon_s3_bucket_name'),
                'Key' => $this->container->getParameter('amazon_s3_bucket_directory') . $filePathDesktop,
                'Body' => file_get_contents($fileDesktopUpload->getRealPath()),
                'ContentType' => 'image/png'
            ]);

            $banner
                ->setFileDesktop($filePathDesktop)
                ->setFileMobile($filePathMobile);

            $em = $this->getDoctrine()->getManager();
            $em->persist($banner);
            $em->flush();

            $this->addFlash('success', sprintf($this->get('translator')->trans("Banner %s has been successfully saved."), $banner->translate()->getName()));

            return $this->redirectToRoute('banners_index');
        }

        return $this->render('AppAdminBundle:Banner:new.html.twig', array(
            'page' => $banner,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing page entity.
     *
     */
    public function editAction(Request $request, Banner $banner = null)
    {
        if (!$banner) {
            $this->addFlash('danger', sprintf($this->get('translator')->trans("Banner not found!")));

            return $this->redirectToRoute('banners_index');
        }

        $editForm = $this->createForm('App\AdminBundle\Form\BannerType', $banner, []);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $fileMobileUpload */
            $fileMobileUpload = $banner->getFileMobileUpload();
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $fileDesktopUpload */
            $fileDesktopUpload = $banner->getFileDesktopUpload();

            if ($fileMobileUpload) {
                $filePathMobile = md5(uniqid()) . '.' . $fileMobileUpload->guessExtension();
                $this->container->get('amazonS3.client')->putObject([
                    'ACL' => 'public-read',
                    'Bucket' => $this->container->getParameter('amazon_s3_bucket_name'),
                    'Key' => $this->container->getParameter('amazon_s3_bucket_directory') . $filePathMobile,
                    'Body' => file_get_contents($fileMobileUpload->getRealPath()),
                    'ContentType' => 'image/png'
                ]);
                $banner->setFileMobile($filePathMobile);
            }

            if ($fileDesktopUpload) {
                $filePathDesktop = md5(uniqid()) . '.' . $fileDesktopUpload->guessExtension();
                $this->container->get('amazonS3.client')->putObject([
                    'ACL' => 'public-read',
                    'Bucket' => $this->container->getParameter('amazon_s3_bucket_name'),
                    'Key' => $this->container->getParameter('amazon_s3_bucket_directory') . $filePathDesktop,
                    'Body' => file_get_contents($fileDesktopUpload->getRealPath()),
                    'ContentType' => 'image/png'
                ]);
                $banner->setFileDesktop($filePathDesktop);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', sprintf($this->get('translator')->trans("Banner %s has been successfully updated."), $banner->translate()->getName()));

            return $this->redirectToRoute('banners_index');
        }

        return $this->render('AppAdminBundle:Banner:edit.html.twig', array(
            'page' => $banner,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a page entity.
     *
     */
    public function deleteAction(Request $request, Banner $banner = null)
    {
        if (!$banner) {
            $this->addFlash('danger', sprintf($this->get('translator')->trans("Banner not found!")));

            return $this->redirectToRoute('banners_index');
        }

        $em = $this->getDoctrine()->getManager();
        $this->get('app.helper')->disableSoftDeletable($em);
        $this->get('app.uploader')->removeS3Objects([
            $banner->getFileDesktop(),
            $banner->getFileMobile()
        ]);
        $em = $this->getDoctrine()->getManager();
        $name = $banner->translate()->getName();
        $em->remove($banner);

        $this->addFlash('success', sprintf($this->get('translator')->trans("Banner %s has been successfully removed."), $name));
        $em->flush();

        return $this->redirectToRoute('banners_index');
    }

    /**
     * Status change.
     *
     */
    public function statusAction(Request $request, Banner $banner = null)
    {

        if (!$banner) {
            $this->addFlash('danger', sprintf($this->get('translator')->trans("Banner not found!")));

            return $this->redirectToRoute('banners_index');
        }

        $em = $this->getDoctrine()->getManager();
        $banner->setActive(!$banner->getActive());
        $em->flush();

        return new JsonResponse([
            'success' => true
        ]);
    }
}
