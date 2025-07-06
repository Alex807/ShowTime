<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\SqlInjectionSafe;

class ArtistTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('real_name', TextType::class, [
                'label' => 'Real Name',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter the artist\'s real name']),
                    new Assert\Regex([
                        'pattern' => "/^[a-zA-Z0-9\s\-\&\.\,\(\)]+$/u",
                        'message' => 'Artist real_name contains invalid characters.',
                    ]),
                    new SqlInjectionSafe(),
                ],
            ])
            ->add('stage_name', TextType::class, [
                'label' => 'Stage Name',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter the artist\'s stage name']),
                    new Assert\Regex([
                        'pattern' => "/^[a-zA-Z0-9\s\-\&\.\,\(\)]+$/u",
                        'message' => 'Artist stage_name contains invalid characters.',
                    ]),
                    new SqlInjectionSafe(),
                ],
            ])
            ->add('music_genre', TextType::class, [
                'label' => 'Music Genre',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter the music genre']),
                    new Assert\Regex([
                        'pattern' => "/^[a-zA-Z0-9\s\-\&\.\,\(\)]+$/u",
                        'message' => 'Music genre contains invalid characters.',
                    ]),
                    new SqlInjectionSafe(),
                ],
            ])
            ->add('instagram_account', TextType::class, [
                'label' => 'Instagram Account',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter the Instagram account']),
                    new Assert\Regex([
                        'pattern' => "/^[a-zA-Z0-9\s\-\&\.\@\,\(\)]+$/u",
                        'message' => 'Instagram account contains invalid characters.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^@/',
                        'message' => 'Instagram account should start with "@".',
                    ]),
                    new SqlInjectionSafe(),
                ],
            ])
            ->add('image_url', TextType::class, [
                'label' => 'Image',
                'required' => true,
                'constraints' => [
                    new Assert\Url(['message' => 'Please enter a valid URL to artist image ']),
                    new SqlInjectionSafe(),
                ],
            ])
            ->add('manager_email', TextType::class, [
                'label' => 'Manager Email',
                'required' => false,
                'constraints' => [
                    new Assert\Email(['message' => 'Invalid email address']),
                    new SqlInjectionSafe(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
