<?php

namespace App\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RequestStack;

class Builder
{
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function mainMenu(RequestStack $requestStacks)
    {
        $translator = $this->container->get('translator');
        $menu = $this->container->get('knp_menu.factory')->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav',
            'id' => 'side-menu'
        ]);

        $pages = $menu->addChild(
            '<i class="fa fa-files-o fa-fw"></i> ' . $translator->trans('Pages') . '<span class="fa arrow"></span>', [
            'uri' => '#',
            'attributes' => [],
            'extras' => [
                'safe_label' => true
            ]]);

        $pages->setChildrenAttributes([
            'class' => 'nav nav-second-level'
        ]);

        $pages->addChild('<i class="fa fa-list" aria-hidden="true"></i> ' . $translator->trans('Pages list'), [
            'route' => 'page_index',
            'extras' => [
                'safe_label' => true
            ]]);

        $pages->addChild('<i class="fa fa-plus-square" aria-hidden="true"></i> ' . $translator->trans('Add page'), [
            'route' => 'page_new',
            'extras' => [
                'safe_label' => true
            ]]);

        $agents = $menu->addChild(
            '<i class="fa fa-user"></i> Agenci<span class="fa arrow"></span>', [
            'uri' => '#',
            'attributes' => [],
            'extras' => [
                'safe_label' => true
            ]]);

        $agents->setChildrenAttributes([
            'class' => 'nav nav-second-level'
        ]);

        $agents->addChild('<i class="fa fa-list" aria-hidden="true"></i> Lista', [
            'route' => 'agents_index',
            'extras' => [
                'safe_label' => true
            ]]);

        $agents->addChild('<i class="fa fa-plus-square" aria-hidden="true"></i> Nowy agent', [
            'route' => 'agents_new',
            'extras' => [
                'safe_label' => true
            ]]);

//        $cards = $menu->addChild(
//            '<i class="fa fa-address-card" aria-hidden="true"></i> ' . $translator->trans('Cards') . '<span class="fa arrow"></span>', [
//            'uri' => '#',
//            'attributes' => [],
//            'extras' => [
//                'safe_label' => true
//            ]]);
//
//        $cards->setChildrenAttributes([
//            'class' => 'nav nav-second-level'
//        ]);
//
//        $cards->addChild('<i class="fa fa-list" aria-hidden="true"></i> ' . $translator->trans('Cards list'), [
//            'route' => 'cards_index',
//            'extras' => [
//                'safe_label' => true
//            ]]);
//
//        $banners = $menu->addChild(
//            '<i class="fa fa-picture-o" aria-hidden="true"></i> ' . $translator->trans('Banners') . '<span class="fa arrow"></span>', [
//            'uri' => '#',
//            'attributes' => [],
//            'extras' => [
//                'safe_label' => true
//            ]]);
//
//        $banners->setChildrenAttributes([
//            'class' => 'nav nav-second-level'
//        ]);
//
//        $banners->addChild('<i class="fa fa-list" aria-hidden="true"></i> ' . $translator->trans('Banners list'), [
//            'route' => 'banners_index',
//            'extras' => [
//                'safe_label' => true
//            ]]);
//
//        $banners->addChild('<i class="fa fa-plus-square" aria-hidden="true"></i> ' . $translator->trans('Add banner'), [
//            'route' => 'banners_new',
//            'extras' => [
//                'safe_label' => true
//            ]]);


        $settings = $menu->addChild(
            '<i class="fa fa-cogs" aria-hidden="true"></i> ' . $translator->trans('Settings') . '<span class="fa arrow"></span>', [
            'uri' => '#',
            'attributes' => [],
            'extras' => [
                'safe_label' => true
            ]]);

        $settings->setChildrenAttributes([
            'class' => 'nav nav-second-level'
        ]);

        $settings->addChild('<i class="fa fa-list" aria-hidden="true"></i> ' . $translator->trans('Settings list'), [
            'route' => 'settings_index',
            'extras' => [
                'safe_label' => true
            ]]);

        return $menu;
    }
}