<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbCustomers', IntegerType::class, [
                'label' => 'Nombre de clients',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer le nombre de clients',
                    ]),
                ]
            ])
            ->add('date', DateType::class, [
                'label' => 'Choisissez une date',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y')+1),
                'format' => 'dd-MM-yyyy',
            ])
            ->add('service', ChoiceType::class, [
                'label' => 'Service : ',
                'choices' => [
                    'Midi' => 'Midi',
                    'Soir' => 'Soir',
                ],
                'attr' => [
                    "class" => "form-select",
                ]
            ])
            // ->add('user')
            ->add('submit', SubmitType::class, [
                'label' => 'Valider votre réservation',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
