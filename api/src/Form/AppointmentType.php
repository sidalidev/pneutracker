<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clientName', TextType::class, ['label' => 'Nom du client'])
            ->add('clientPhone', TextType::class, ['label' => 'Telephone'])
            ->add('vehiclePlate', TextType::class, ['label' => 'Immatriculation'])
            ->add('tireReference', TextType::class, ['label' => 'Reference pneu'])
            ->add('quantity', IntegerType::class, ['label' => 'Quantite'])
            ->add('scheduledAt', DateTimeType::class, ['label' => 'Date du rendez-vous'])
            ->add('franchise', ChoiceType::class, [
                'label' => 'Centre',
                'choices' => [
                    'Nantes Centre' => 'Nantes Centre',
                    'Rennes Nord' => 'Rennes Nord',
                    'Brest Ocean' => 'Brest Ocean',
                ],
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Notes',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
            'csrf_protection' => false,
        ]);
    }
}
