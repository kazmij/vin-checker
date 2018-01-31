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

class PageType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\AdminBundle\Entity\Page',
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
                        'label' => 'Description',
                        'attr' => [
                            'class' => 'tinymce',
                            'data-theme' => 'advanced',
                        ],
                    ],
                    'seoTitle' => [
                        'label' => 'Seo title',
                    ],
                    'seoDescription' => [
                        'label' => 'Seo description',
                    ],
                    'seoKeywords' => [
                        'label' => 'Seo keywords',
                    ],
                    'slug' => [
                        'required' => false,
                        'attr' => [
                            'disabled' => 1
                        ]
                    ]
                ],
                'exclude_fields' => ['place', 'shortDescription']
            ])
            ->add('type');


        if (!$builder->getData()) {
            $builder->add('type');
        }

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
