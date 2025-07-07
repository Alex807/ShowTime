<?php

namespace App\Form;

use App\Entity\FestivalEdition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\SqlInjectionSafe;
use Symfony\Component\Validator\Constraints\Range;

class FestivalEditionTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('year_happened', IntegerType::class, [
                'label' => 'Year',
                'required' => true,
                'constraints' => [
                    new Assert\Positive(['message' => 'Year must be a positive integer']),
                ],
            ])
            ->add('venue_name', TextType::class, [
                'label' => 'Venue Name',
                'required' => true,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => "/^[a-zA-Z0-9\s\-\,\(\)]+$/u",
                        'message' => 'Venue name contains invalid characters.',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'constraints' => [
                    new Assert\Length(['max' => 500, 'maxMessage' => 'Description cannot exceed {{ limit }} characters']),
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'required' => true,
                'choices' => [
                    'Completed' => 'completed',
                    'Upcoming' => 'upcoming',
                    'Cancelled' => 'cancelled',
                    'Postponed' => 'postponed',
                    'Sold Out' => 'sold_out',
                ],
                'constraints' => [
                    new Assert\Choice([
                        'choices' => ['completed', 'upcoming', 'cancelled', 'postponed', 'sold_out'],
                        'message' => 'Please choose a valid status: completed, upcoming, cancelled, postponed, sold_out.',
                    ]),
                ],
            ])
            ->add('start_date', DateType::class, [
                'label' => 'Start Date',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('end_date', DateType::class, [
                'label' => 'End Date',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('people_capacity', IntegerType::class, [
                'label' => 'People Capacity',
                'required' => true,
                'constraints' => [
                    new Range(['min' => 10000, 'max' => PHP_INT_MAX, 'notInRangeMessage' => 'Capacity must be at least {{ min }} people.']),
                ],

            ])
            ->add('terms_conditions', TextareaType::class, [
                'label' => 'Terms and Conditions',
                'required' => true,
                'constraints' => [
                    new Assert\Length(['max' => 2000, 'maxMessage' => 'Description cannot exceed {{ limit }} characters']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FestivalEdition::class,
        ]);
    }
}
