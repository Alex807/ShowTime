<?php

namespace App\Form;

use App\Entity\UserAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Range;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // UserAccount fields
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter an email']),
                    new Email(['message' => 'Please enter a valid email address']),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a password']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])

            // UserDetails fields
            ->add('firstName', TextType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your first name']),
                    new Length(['max' => 100]),
                ],
            ])
            ->add('lastName', TextType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your last name']),
                    new Length(['max' => 100]),
                ],
            ])
            ->add('age', IntegerType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your age']),
                    new Range(['min' => 13, 'max' => 120, 'notInRangeMessage' => 'Age must be at least {{ min }} years.']),
                ],
            ])
            ->add('phoneNo', TelType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Length(['max' => 20]),
                ],
            ])

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue(['message' => 'You should agree to our terms.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserAccount::class,
        ]);
    }
}
