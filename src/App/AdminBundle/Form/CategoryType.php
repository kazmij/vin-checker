<?php

namespace App\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\AdminBundle\Repository\CategoryRepository;
use App\AdminBundle\Form\Type\TreeType;

class CategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        $builder
            ->add('translations', TranslationsType::class, [
                'label' => 'Translations',
                'fields' => [
                    'name' => [
                        'label' => 'Name'
                    ],
                    'description' => [
                        'label' => 'Description'
                    ]
                ],
                'exclude_fields' => ['slug', 'place', 'shortDescription']
            ])
            ->add('parentNode', TreeType::class, array(
                'label' => 'Parent category',
                'required' => false,
                'type' => $options['type'],
                'class' => 'App\AdminBundle\Entity\Category',
                'choice_label' => function ($category) {
                    return $category->translate()->getName();
                },
                'query_builder' => function (CategoryRepository $er) use ($data) {
                    if ($data && $data->getId()) {
                        return $er->createQueryBuilder('c')
                            ->andWhere('c.id = ' . $data->getId());
                    } else {
                        return $er->createQueryBuilder('c');
                    }
                }
            ))
            ->add('contentClass')
            ->add('active');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\AdminBundle\Entity\Category',
            'type' => 'news'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_adminbundle_category';
    }


}
