<?php

namespace App\AdminBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\AdminBundle\Entity\Banner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\AdminBundle\Repository\PortalRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BannerType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\AdminBundle\Entity\Banner'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $editMode = $builder->getData()->getId() > 0;

        $builder
            ->add('translations', TranslationsType::class, [
                'label' => 'Translations',
                'fields' => [
                    'name' => [
                        'label' => 'Name',
                    ]
                ],
                'exclude_fields' => ['description', 'shortDescription', 'seoTitle', 'seoDescription', 'slug', 'seoKeywords', 'place', 'type']
            ])
            ->add('fileMobileUpload', null, [
                'required' => !$editMode
            ])
            ->add('fileDesktopUpload', null, [
                'required' => !$editMode
            ])
            ->add('places', null, [
                'expanded' => true,
                'required' =>true
            ])
            ->add('websiteUrl', UrlType::class, [
                'required' => false
            ]);

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
        return 'app_adminbundle_banner';
    }


}
