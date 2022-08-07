<?php

namespace App\Form;

use App\Entity\Food;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Length;

class FoodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label'=> 'Titre :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez taper un nom',
                    ]),
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez taper une description',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Votre description est trop longue',
                    ])
                ]
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez taper un prix',
                    ]),
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type : ',
                'choices' => [
                    'Entrée' => 'Entrée',
                    'Plat' => 'Plat',
                    'Dessert' => 'Dessert',
                    'Boisson' => 'Boisson',
                ],
                'attr' => [
                    "class" => "form-select",
                ]
            ])
            ->add('picture', FileType::class, [
                'label' => 'Photo :',
                'data_class' => null,
                'constraints' => [
                    new Image([
                       'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Les formats autorisés sont .jpg ou .png',
                        'maxSize' => '10M',
                        'maxSizeMessage' => 'Le poids maximal du fichier est : {{ limit }} {{ suffix }} ({{ name }}: {{ size }} {{ suffix }})',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['picture'] ? 'Modifier' : 'Ajouter',
                'validate' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Food::class,
            'allow_file_upload' => true,
            'picture' => null,
        ]);
    }
}
