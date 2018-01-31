<?php

namespace App\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;

/**
 * Class ElFinderCustomType.
 */
class ElFinderCustomType extends ElFinderType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['enable'] = $options['enable'];

        if ($options['enable']) {
            $view->vars['instance']   = $options['instance'];
            $view->vars['homeFolder'] = $options['homeFolder'];
        }
        $view->vars['multiple'] = $options['multiple'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'enable'        => true,
                'instance'      => 'default',
                'homeFolder'    => '',
                'multiple'    => false,
            ))
            ->setAllowedTypes('enable', 'bool')
            ->setAllowedTypes('instance', array('string', 'null'))
            ->setAllowedTypes('homeFolder', array('string', 'null'))
            ->setAllowedTypes('multiple', 'bool');
    }

}
