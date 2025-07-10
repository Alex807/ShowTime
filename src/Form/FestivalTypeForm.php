<?php

namespace App\Form;

use App\Entity\Festival;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\SqlInjectionSafe;

class FestivalTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Festival Name',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter a festival name']),
                    new Assert\Length(['max' => 255, 'maxMessage' => 'Festival name cannot exceed {{ limit }} characters']),
                    new Assert\Regex([
                        'pattern' => "/^[a-zA-Z0-9\s\-\&\.\,\(\)]+$/u",
                        'message' => 'Name contains invalid characters.',
                    ]),
                    new SqlInjectionSafe(),
                ],
            ])
            ->add('country', TextType::class, [
                'label' => 'Country',
                'required' => true,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => "/^[a-zA-Z0-9\s\-\&\.\,\(\)]+$/u",
                        'message' => 'Country contains invalid characters.',
                    ]),
                    new SqlInjectionSafe(),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'required' => true,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => "/^[a-zA-Z0-9\s\-\,\(\)]+$/u",
                        'message' => 'City contains invalid characters.',
                    ]),
                    new SqlInjectionSafe(),
                ],
            ])
            ->add('street_name', TextType::class, [
                'label' => 'Street Name',
                'required' => true,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => "/^[a-zA-Z0-9\s\-\&\.\,\(\)]+$/u",
                        'message' => 'Street name contains invalid characters.',
                    ]),
                    new SqlInjectionSafe(),
                ],
            ])
            ->add('street_no', IntegerType::class, [
                'label' => 'Street Number',
                'required' => true,
                'constraints' => [
                    new Assert\Positive(['message' => 'Street number must be a positive integer']),
                ],
            ])
            ->add('festival_email', TextType::class, [
                'label' => 'Festival Email',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter a festival email']),
                    new Assert\Email(['message' => 'Invalid email address']),
                    new SqlInjectionSafe(),
                ],
            ])
            ->add('website', TextType::class, [
                'label' => 'Website',
                'required' => false,
                'constraints' => [
                    new Assert\Url(['message' => 'Please enter a valid URL for website']),
                    new SqlInjectionSafe(),
                ],
            ])
            ->add('logo_url', TextType::class, [
                'label' => 'Logo',
                'required' => false,
                'constraints' => [
                    new Assert\Url(['message' => 'Please enter a valid URL for logo']),
                    new SqlInjectionSafe(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Festival::class,
        ]);
    }
}
