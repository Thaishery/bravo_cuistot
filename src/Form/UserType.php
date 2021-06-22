<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'administrateur' => 'ROLE_ADMIN',
                    'cuistot' => 'ROLE_USER',
                    'modérateur' => 'ROLE_MODERATEUR'
                ],
                'multiple' => true,
                'expanded' => false
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'constraints' => [
                new NotBlank([
                'message' => 'Entrez un mot de passe, s\'il vous plait',
                ]),
                new Length([
                'min' => 6,
                'minMessage' => 'Votre mot de passe devrait avoir au moins {{ limit }} caractères',
                // max length allowed by Symfony for security reasons
                'max' => 4096,
                ]),
                ]
                ])
                
            
            ->add('avatar')
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
