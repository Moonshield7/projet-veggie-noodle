<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class,[
            'label' => 'Email',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ ne peut être vide',
                ]),
                new Length([
                    'max' => 180,
                    'maxMessage' => 'Votre email ne peut dépasser {{ limit }} caractères',
                ]),
                new Email([
                    'message' => 'Votre email n\'est pas au bon format: ex. mail@example.com'
                ]),
            ],
        ])
            // ->add('roles')
            ->add('password', PasswordType::class,[
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut être vide',
                    ]),
                    new Length([
                        'max' => 255,
                        'min' => 4,
                        'maxMessage' => 'Votre mot de passe ne peut dépasser {{ limit }} caractères',
                        'minMessage' => 'Votre mot de passe doit avoir au minimum {{ limit }} caractères',
                    ]),
                ],
            ])
            // ->add('username', TextType::class, [
            //     'label' => 'Pseudo :',
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Veuillez choisir un nom d\'utilisateur',
            //         ]),
            //     ]
            // ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre nom',
                    ]),
                ]
            ])
            ->add('phoneNumber', TextType::class,[
                'label' => "Téléphone",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut être vide',
                    ]),
                    new Length([
                        'max' => 10,
                        'min' => 10,
                        'maxMessage' => 'Votre numéro de téléphone ne peut dépasser {{ limit }} chiffres',
                        'minMessage' => 'Votre numéro de téléphone doit avoir au minimum {{ limit }} chiffres',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->define('handleDates');
        
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
