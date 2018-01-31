<?php

namespace App\AdminBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\Exception\RuntimeException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Bridge\Doctrine\Form\EventListener\MergeDoctrineCollectionListener;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityRepository;
//use Doctrine\Common\Persistence\ManagerRegistry,
use Symfony\Component\OptionsResolver\OptionsResolver;

class TreeType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $repository = $options['em']->getRepository($options['class']);
        if($repository){
            if(method_exists($repository, 'getJsTree')){
                $data = $form->getParent()->getData();
                $view->vars['tree_data'] = $repository->getJsTree($options['type'], $data);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefined(['type']);
        $resolver->addAllowedTypes('type', 'string');
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
