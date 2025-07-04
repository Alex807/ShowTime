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

class FestivalEditionTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('year_happened', IntegerType::class, [
                'label' => 'Year',
                'required' => true,
            ])
            ->add('venue_name', TextType::class, [
                'label' => 'Venue Name',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'required' => true,
                'choices' => [
                    'Planned' => 'planned',
                    'Ongoing' => 'ongoing',
                    'Completed' => 'completed',
                    'Cancelled' => 'cancelled'
                ]
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
            ])
            ->add('terms_conditions', TextareaType::class, [
                'label' => 'Terms and Conditions',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FestivalEdition::class,
        ]);
    }
}
