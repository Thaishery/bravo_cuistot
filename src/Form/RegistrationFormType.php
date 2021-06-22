<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
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

             ->add('agreeTerms', CheckboxType::class, [
                 'mapped' => false,
                 'constraints' => [
                     new IsTrue([
                         'message' => 'You should agree to our terms.',
                     ]),
                 ],
             ])

             ->add('plainPassword', RepeatedType::class, [
                 'type' => PasswordType::class,
                 'mapped' => false,
                 'constraints' => [

                     new NotBlank([
                         'message' => 'Veuillez entrer un mot de passe.',
                     ]),

                     new Length([
                         'min' => 6,
                         'minMessage' => 'Votre mot de passe doit au minimum contenir {{ limit }} caractères.',
                         // Longueur maximale autorisée poru raisons de sécurité
                         'max' => 4096,
                     ]),

                     // regex caractére spécial : 
                     new Assert\Regex([
                         'pattern' => '/[^A-Za-z0-9]+/',
                         'message' => 'Vous devez saisir au moins 1 caractére spécial.'
                         ]),

                     //regex Majuscule : 
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
            ->add('email')
            ->add('avatar')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
