<?php

namespace App\Form;

use App\Entity\Purchase;
use App\Entity\TicketType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class PurchaseTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $ticketTypes = $options['ticket_types'];

        $builder
            ->add('ticket_type', EntityType::class, [
                'class' => TicketType::class,
                'label' => 'Ticket Type',
                'choices' => $ticketTypes,
                'choice_label' => function (TicketType $ticketType) {
                    return sprintf('%s ($%s)', $ticketType->getName(), $ticketType->getPrice());
                },
                'required' => true,
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantity',
                'required' => true,
                'constraints' => [
                    new GreaterThanOrEqual(['value' => 1, 'message' => 'Quantity must be at least 1.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('ticket_types');
        $resolver->setDefaults([
            'data_class' => Purchase::class,
        ]);
    }
}
