<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;



 class RegistrationFormType extends AbstractType {

     public function buildForm (FormBuilderInterface $builder, array $options) {

         $builder

             // * Login :

                 ->add('login', TextType::class,[
                     'constraints' => [
                         new Assert\Regex([
                             'pattern' => '/^[a-zA-Z0-9]*$/',
                             'message' => 'Votre login ne peut comporter que des caractéres alphanumérique.'
                         ])
                     ],
                     'label_attr' => ['class' => 'form-label'],
                     'required' => true,
                 ])
             
             // * Adresse électronique (Email) *

                 ->add('email', RepeatedType::class,[
                     'type' => EmailType::class,
                     'constraints' => [
                         new Assert\Regex([
                             'pattern' => '/^\w*[a-zA-Z0-9-_âêîôûäëïöüéèàçÂÊÎÔÛÄËÏÖÜÀÆæÇÉÈŒœÙ]*+@\w*[a-zA-Z0-9-_âêîôûäëïöüéèàçÂÊÎÔÛÄËÏÖÜÀÆæÇÉÈŒœÙ]*+\.\w+$/',
                             'message' => 'Email invalide'
                         ])
                     ],
                     'invalid_message' => 'Les champs email doivent correspondre.',
                     'options' => ['attr' => ['class' => 'email-field']],
                     'required' => true,
                     'first_options'  => ['label' => 'email','label_attr' => ['class' => 'form-label']],
                     'second_options' => ['label' => 'Veuillez répéter votre email','label_attr' => ['class' => 'form-label']],
                 ])

             // * Photo de profil *

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
                     'label_attr' => ['class' => 'form-label'],
                     'required' => false,
                 ])

            //  * Mot de passe :

                 ->add('plainPassword', RepeatedType::class, [
                     'type' => PasswordType::class,
                     'mapped' => false,
                     'constraints' => [

                         new NotBlank([
                             'message' => 'Veuillez entrer un mot de passe.',
                         ]),

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
                     'required' => true,
                     'first_options'  => ['label' => 'Mot de passe','label_attr' => ['class' => 'form-label']],
                     'second_options' => ['label' => 'Veuillez répéter votre mot de passe','label_attr' => ['class' => 'form-label']],
                     'label_attr' => ['class' => 'form-label'],
                 ])

             // * Agree Terms (C.G.U.) :

                 ->add('agreeTerms', CheckboxType::class, [
                     'mapped' => false,
                     'constraints' => [
                         new IsTrue([
                             'message' => 'Veuillez cocher la case conditions du site.',
                         ]),
                     ],
                 ]);

     }

     public function configureOptions(OptionsResolver $resolver) {

             $resolver->setDefaults([
                 'data_class' => User::class,
             ]);

     }

 }
