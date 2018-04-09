<?php

namespace App\FrontBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\AdminBundle\Entity\CarPhoto;
use App\AdminBundle\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FOS\UserBundle\Util\LegacyFormHelper;

class CarType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\AdminBundle\Entity\Car',
            'manufacturers' => [],
            'models' => [],
            'trims' => []
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $years = range(1980, date('Y'));
        $tmp = [];
        foreach ($years as $key => $value) {
            $tmp[$value] = $value;
        }
        krsort($tmp);
        $years = $tmp;

        $builder
            ->add('manufacturerData', ChoiceType::class, array(
                'label' => 'Producent',
                'placeholder' => 'Wybierz producenta',
                'required' => true,
                'choices' => $options['manufacturers']
            ))
            ->add('modelData', ChoiceType::class, array(
                'label' => 'Model',
                'placeholder' => 'Wybierz model',
                'required' => true,
                'disabled' => empty($options['models']),
                'choices' => $options['models']
            ))
            ->add('trimData', ChoiceType::class, array(
                'label' => 'Typ',
                'placeholder' => 'Wybierz typ',
                'disabled' => empty($options['trims']),
                'required' => false,
                'choices' => $options['trims']
            ))
            ->add('vin', null, [
                'required' => true,
                'label' => "VIN"
            ])
            ->add('insurer', null, [
                'required' => false,
                'label' => "Aktualny ubezpieczyciel"
            ])
            ->add('policyNumber', null, [
                'required' => false,
                'label' => "Numer ostatniej polisy"
            ])
            ->add('policyDate', DateType::class, [
                'required' => false,
                'label' => "Data ważności ostatniej polisy",
                'widget' => 'single_text',
                'html5' => false,
            ])
            ->add('photosToRemove', HiddenType::class, [
                'mapped' => false
            ])
            ->add('color', null, [
                'required' => true,
                'label' => "Kolor"
            ])
            ->add('yearOfManufacture', ChoiceType::class , array(
                'label' => 'Rok produkcji',
                'placeholder' => 'Wybierz rok produkcji',
                'required' => true,
                'choices' => $years
            ))
            ->add('description', null, [
                'required' => true,
                'label' => "Opis"
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
