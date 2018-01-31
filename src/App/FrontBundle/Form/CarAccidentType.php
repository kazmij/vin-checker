<?php

namespace App\FrontBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\AdminBundle\Entity\CarPhoto;
use App\AdminBundle\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Validator\Constraints\DateTime;

class CarAccidentType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\AdminBundle\Entity\CarAccidentHistory'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', null, [
                'required' => true,
                'label' => "Opis"
            ])
            ->add('place', TextType::class, [
                'required' => true,
                'label' => "Miejsce zdarzenia"
            ])
            ->add('photosToRemove', HiddenType::class, [
                'mapped' => false
            ])
            ->add('accidentDate', DateType::class, [
                'label' => 'Data wystąpienia szkody',
                'widget' => 'single_text',
                'html5' => false,
            ])
            ->add('mileagesHistory', CollectionType::class, [
                'label' => 'Przebieg',
                'required' => true,
                'entry_type' => CarMileageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false
            ])
            ->add('photos', CollectionType::class, [
                'label' => 'Zdjecia',
                'required' => false,
                'entry_type' => CarPhotoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false
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
        return 'app_car';
    }


}
