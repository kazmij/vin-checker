<?php

namespace App\AdminBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\AdminBundle\Repository\PortalRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SettingType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\AdminBundle\Entity\Setting',
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', TranslationsType::class, [
                'label' => 'Translations',
                'fields' => [
                    'name' => [
                        'label' => 'Name',
                    ],
                    'description' => [
                        'label' => 'Value',
                    ]
                ],
                'exclude_fields' => ['shortDescription', 'seoTitle', 'seoDescription', 'slug', 'seoKeywords', 'place', 'type']
            ]);

        //if (!$builder->getData()) {
            $builder->add('type');
       // }

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            array($this, 'onPreSetData')
        );
    }

    /**
     * @param FormEvent $event
     */
    public function onPreSetData(FormEvent $event)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_adminbundle_page';
    }


}
