<?php

namespace App\Form;

use App\Entity\Festival;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FestivalTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Festival Name',
                'required' => true,
            ])
            ->add('country', TextType::class, [
                'label' => 'Country',
                'required' => true,
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'required' => true,
            ])
            ->add('street_name', TextType::class, [
                'label' => 'Street Name',
                'required' => true,
            ])
            ->add('street_no', IntegerType::class, [
                'label' => 'Street Number',
                'required' => true,
            ])
            ->add('festival_email', TextType::class, [
                'label' => 'Festival Email',
                'required' => true,
            ])
            ->add('website', TextType::class, [
                'label' => 'Website URL',
                'required' => false,
            ])
            ->add('logo_url', TextType::class, [
                'label' => 'Logo URL',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Festival::class,
        ]);
    }
}
