<?php

namespace App\AdminBundle\Twig\Extension;

use App\AdminBundle\Entity\Banner;
use App\AdminBundle\Entity\Content;
use App\AdminBundle\Entity\Event;
use App\AdminBundle\Entity\News;
use App\AdminBundle\Entity\Page;
use Symfony\Component\DependencyInjection\Container;

class AppExtension extends \Twig_Extension
{

    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getName()
    {
        return 'app_twig_extension';
    }

    public function getFilters()
    {
        return [
            'relativePath' => new \Twig_SimpleFilter('relativePath', [$this, 'relativePath']),
            'amazonUrl' => new \Twig_SimpleFilter('amazonUrl', [$this, 'amazonUrl']),
            'addslashes' => new \Twig_SimpleFilter('addslashes', [$this, 'fnAddslashes']),
            'removeWhiteSpace' => new \Twig_SimpleFilter('removeWhiteSpace', [$this, 'removeWhiteSpace']),
            'showAmazonImage' => new \Twig_SimpleFilter('showAmazonImage', [$this, 'showAmazonImage'])
        ];
    }

    public function getFunctions()
    {

        return [
            new \Twig_SimpleFunction('getContentBy', array($this, 'getContentBy')),
            new \Twig_SimpleFunction('getParameter', array($this, 'getParameter')),
            new \Twig_SimpleFunction('getCategories', array($this, 'getCategories')),
            new \Twig_SimpleFunction('getBlockContent', array($this, 'getBlockContent')),
            new \Twig_SimpleFunction('isHome', array($this, 'isHome')),
            new \Twig_SimpleFunction('getContentType', array($this, 'getContentType')),
            new \Twig_SimpleFunction('inArray', array($this, 'inArray')),
            new \Twig_SimpleFunction('isFileExists', array($this, 'isFileExists')),
            new \Twig_SimpleFunction('homePath', array($this, 'homePath')),
            new \Twig_SimpleFunction('seoSettings', array($this, 'getSeoSettings')),
            new \Twig_SimpleFunction('getSetting', array($this, 'getSetting')),
            new \Twig_SimpleFunction('getBanners', array($this, 'getBanners')),
            new \Twig_SimpleFunction('getUploadPath', array($this, 'getUploadPath'))
        ];
    }

    public function relativePath($url)
    {
        $requstStack = $this->container->get('request_stack');
        $request = $requstStack->getCurrentRequest();

        return str_replace([$request->server->get('HTTP_HOST'), 'http://', 'https://'], '', $url);
    }

    public function amazonUrl($key)
    {
        $client = $this->container->get('amazonS3.client');
        $url = $client->getObjectUrl($this->container->getParameter('amazon_s3_bucket_name'), str_replace('//', '/', $this->container->getParameter('amazon_s3_bucket_directory') . $key));

        if ($url && $this->container->hasParameter('amazon_s3_alias') && $this->container->hasParameter('amazon_s3_alias_directory')) {
            $url = $this->container->getParameter('amazon_s3_alias') . str_replace('//', '/', $this->container->getParameter('amazon_s3_alias_directory') . $key);
        }

        return $url ? ($url . '?crossorigin') : '';
    }

    public function showAmazonImage($key)
    {
        $response = $this->container->get('amazons3.client')->getObject([
            'Bucket' => $this->container->getParameter('amazon_s3_bucket_name'),
            'Key' => $this->container->getParameter('amazon_s3_bucket_directory') . $key,
        ]);

        header("Content-Type: {$response['ContentType']}");
        echo $response['Body'];
        exit;
    }

    public function fnAddslashes($string)
    {

        return addslashes($string);
    }

    /**
     * @param string $field
     * @param string $value
     * @return null|Content
     * @throws \Throwable
     */
    public function getContentBy($field, $value)
    {

        return $this->container->get('app.content_repository')->findOneBy([
            $field => $value
        ]);
    }

    /**
     * @return array
     */
    public function getParameter($name)
    {

        return $this->container->hasParameter($name) ? $this->container->getParameter($name) : null;
    }

    /**
     * @param string|null $type
     * @return array
     */
    public function getCategories($type = null)
    {

        return $this->container->get('app.category_repository')->getCategories($type);
    }

    /**
     * Get block content
     *
     * @param string $type
     */
    public function getBlockContent($type)
    {
        $block = $this->container->get('app.content_repository')->getContentByType($type);

        return $block ? $block->translate()->getDescription() : null;
    }


    /**
     * @return bool
     */
    public function isHome()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();

        return $request->attributes->get('_controller') == 'App\FrontBundle\Controller\DefaultController::indexAction';
    }

    public function getContentType(Content $type)
    {
        $reflection = new \ReflectionClass($type);
        $translator = $this->container->get('translator');

        switch ($reflection->getShortName()) {
            case 'Page':
                return $translator->trans('Page');
        }

        return '';
    }

    /**
     * Chekc if is in array
     *
     * @param string $needle
     * @param array $haystack
     * @return bool
     */
    public function inArray($needle, $haystack)
    {

        return in_array($needle, $haystack);
    }


    public function isFileExists($path)
    {

        return file_exists($path);
    }

    public function homePath()
    {
        return realpath($this->container->get('kernel')->getRootDir());
    }

    public function getSeoSettings()
    {

        return $this->container->get('app.setting_repository')->getSeoSettings();
    }

    public function getSetting($key)
    {

        return $this->container->get('app.setting_repository')->getSetting($key);
    }

    public function removeWhiteSpace($string)
    {

        return preg_replace('/\s+/', '', $string);
    }

    public function getBanners($place)
    {

        $banners = $this->container->get('app.banner_repository')->getBanners($place);

        return $this->container->get('templating')->render('@AppFront/layout/banners.html.twig', [
            'banners' => $banners
        ]);
    }

    public function getUploadPath() {
        return $this->container->get('kernel')->getUploadDir();
    }
}
