<?php

namespace App\Form;

use App\Entity\EditionReview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class EditionReviewTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating_stars', ChoiceType::class, [
                'label' => 'Rating',
                'choices' => [
                    '1 Star' => 1,
                    '2 Stars' => 2,
                    '3 Stars' => 3,
                    '4 Stars' => 4,
                    '5 Stars' => 5,
                ],
                'required' => true,
                'constraints' => [
                    new Range([
                        'min' => 1,
                        'max' => 5,
                        'notInRangeMessage' => 'Rating must be between 1 and 5 stars.',
                    ]),
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Comment',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Comment cannot be empty.']),
                    new Length([
                        'max' => 500,
                        'maxMessage' => 'Comment cannot be longer than {{ limit }} characters.',
                    ]),
                ],
                'attr' => ['class' => 'form-control', 'rows' => 5],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EditionReview::class,
        ]);
    }
}
