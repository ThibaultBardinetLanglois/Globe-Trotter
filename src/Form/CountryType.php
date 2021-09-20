<?php

namespace App\Form;

use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CountryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'endroit'
            ])
            ->add('littleDescription', TextareaType::class, [
                'label' => 'Phrase d\'introduction',
                'attr' => [
                    'placeholder' => 'Faîtes une petite phrase d\'ammorce sur le lieu',
                    'cols' => '30',
                    'rows' => '4'
                ],
            ])
            ->add('bigDescription', TextareaType::class, [
                'label' => 'Description de l\'endroit',
                'attr' => [
                    'placeholder' => 'Là c\'est bon, lâchez-vous',
                    'cols' => '30',
                    'rows' => '10'
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => $options['image_required'],
                'constraints' => [
                    new Image([
                        'maxSize' => '500k',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Country::class,
            'image_required' => false
        ]);
    }
}
