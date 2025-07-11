<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\EditionArtist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditionArtistTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('artist', EntityType::class, [
                'class' => Artist::class,
                'choice_label' => 'stage_name',
                'choice_value' => 'id',
                'label' => 'Artist',
                'required' => true,
                'attr' => ['class' => 'form-select'],
            ])
            ->add('isHeadliner', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'label' => 'Headliner',
                'required' => true,
                'attr' => ['class' => 'form-select'],
            ])
            ->add('performanceDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Performance Date',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('startTime', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Start Time',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('endTime', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'End Time',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EditionArtist::class,
        ]);
    }
}
