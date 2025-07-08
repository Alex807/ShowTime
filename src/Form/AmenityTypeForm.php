<?php

namespace App\Form;

use App\Entity\Amenity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\SqlInjectionSafe;
use Symfony\Component\Validator\Constraints\Range;

class AmenityTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter the amenity name']),
                    new Assert\Regex([
                        'pattern' => "/^[a-zA-Z0-9\s\-\&\.\,\(\)]+$/u",
                        'message' => 'Name contains invalid characters.',
                    ]),
                    new SqlInjectionSafe(),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 2000, 'maxMessage' => 'Description cannot exceed {{ limit }} characters']),
                ],
            ])
            ->add('people_capacity', IntegerType::class, [
                'label' => 'People Capacity',
                'required' => true,
                'constraints' => [
                    new Assert\Positive(['message' => 'People capacity must be a positive integer']),
                    new Range(['min' => 2, 'max' => 50, 'notInRangeMessage' => 'Capacity must be between {{ min }} - {{ max }} people.']),
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Price',
                'required' => true,
                'constraints' => [
                    new Assert\Positive(['message' => 'Price must be a positive number']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Amenity::class,
        ]);
    }
}
