<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder          
            ->add('avatar',
            FileType::class, [
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/bmp',
                                'image/gif',
                                'image/png',
                                'image/svg+xml',
                                'image/tiff',
                                'image/webp',
                                ]
                        ])
                            ],
                            'required'   => false,
                    'mapped' => false,
                ]
             )
            //  * Mot de passe récupéré de RegistrationFormType :

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'constraints' => [

                    // * Contraintes: nombre de caractères min : 

                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit au minimum contenir {{ limit }} caractères.',
                        // Longueur maximale autorisée poru raisons de sécurité
                        'max' => 4096,
                    ]),

                    // * Regex caractére spécial : 

                    new Assert\Regex([
                        'pattern' => '/[^A-Za-z0-9]+/',
                        'message' => 'Vous devez saisir au moins 1 caractére spécial.'
                    ]),

                    // * Regex caractére Majuscule : 

                    new Assert\Regex([
                        'pattern' => '/[A-Z]+/',
                        'message' => 'Vous devez saisir au moins 1 Majuscule.'
                    ]),
                ],
                'invalid_message' => 'Les champs mot de passe doivent correspondre',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                //le mot de passe n'est pas requis
                'first_options'  => ['label' => 'Mot de passe','label_attr' => ['class' => 'form-label']],
                'second_options' => ['label' => 'Veuillez répéter votre mot de passe','label_attr' => ['class' => 'form-label']],
                'label_attr' => ['class' => 'form-label'],
            ]) 
            ->add('email', EmailType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
