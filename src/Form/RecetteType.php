<?php

namespace App\Form;

use App\Entity\Alimentation;
use App\Entity\Cuisson;
use App\Entity\Plats;
use App\Entity\Recette;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un nom de recette.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/[^a-zA-ZàâäêéèëîïôöùûüÀÂÄÊËÎÏÔÖÙÛÜŒœÇç0-9 ]+/',
                        'match' => false,
                        'message' => 'Ce champ ne peut contenir que des caractéres alphabétiques, accentuation incluse.',
                    ])
                    ],
                    'label' => 'Nom de la recette'
            ])
            ->add('image',
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
            ])
            ->add('temps_preparation', NumberType::class,[
                'constraints' =>[
                    new NotBlank([
                        'message' => 'Veuillez entrer un temps de préparation',
                    ]),
                    //1er caractére est dif de 0 (marche aussi avec 00 car symfony considére 00 comme 0) : 
                    new Assert\Regex([
                        'pattern' => '/^0{1}/',
                        'match' => false,
                        'message' => 'ce champ ne peut être vide'
                    ]),
                    //au maximum 2 caractéres
                    new Assert\Regex([
                        'pattern' => '/^.{4,}/',
                        'match' => false,
                        'message' => 'Ce champ peut contenir au maximum 3 charactéres'
                    ]),
                    //uniquement des chiffre (le NumberType s'en charge deja): 
                ],
                'invalid_message'=>'Ce champ peut contenir uniquement un nombre, comprit entre 1 et 999'
            ])
            ->add('temps_cuisson', NumberType::class,[
                'constraints' =>[
                    new NotBlank([
                        'message' => 'Veuillez entrer un temps de cuisson, mettez 0 si votre recette ne necessite pas de cuisson.',
                    ]),
                    //au maximum 2 caractéres
                    new Assert\Regex([
                        'pattern' => '/^.{4,}/',
                        'match' => false,
                        'message' => 'Ce champ peut contenir au maximum 3 charactéres'
                    ]),
                    //uniquement des chiffre (le NumberType s'en charge deja): 
                ],
                'invalid_message'=>'Ce champ peut contenir uniquement un nombre, comprit entre 1 et 999'
            ])
            ->add('nb_personnes', NumberType::class,[
                'constraints' =>[
                    new NotBlank([
                        'message' => 'Veuillez entrer le nombre de pars pers.',
                    ]),
                    //1er caractére est dif de 0 (marche aussi avec 00 car symfony considére 00 comme 0) : 
                    new Assert\Regex([
                        'pattern' => '/^0{1}/',
                        'match' => false,
                        'message' => 'Ce champ ne peut être vide'
                    ]),
                    //au maximum 2 caractéres
                    new Assert\Regex([
                        'pattern' => '/^.{3,}/',
                        'match' => false,
                        'message' => 'Ce champ peut contenir au maximum 2 charactéres'
                    ]),
                    //uniquement des chiffre (le NumberType s'en charge deja): 
                ],
                'invalid_message'=>'Ce champ peut contenir uniquement un nombre, comprit entre 1 et 99'
            ])
            ->add('difficulte', NumberType::class,[
                'constraints' =>[
                    new NotBlank([
                        'message' => 'Veuillez entrer une difficulté.',
                    ]),
                    // 1er caractére comprit entre 1 et 3 
                    new Assert\Regex([
                        'pattern' => '/^[1-3]{1}/',
                        'message' => 'Ce champ ne peut varier que de 1 a 3.',
                    ]),
                    // 1er caractére ne peut etre 0 
                    new Assert\Regex([
                        'pattern' => '/^0{1}/',
                        'match' => false,
                        'message' => 'ce champ ne peut être vide'
                    ]),
                    //au maximum 1 caractéres
                    new Assert\Regex([
                        'pattern' => '/^.{2,}/',
                        'match' => false,
                        'message' => 'Ce champ peut contenir au maximum 1 charactéres'
                    ]),
                ],
                'invalid_message'=>'Ce champ peut contenir uniquement un nombre, comprit entre 1 et 99'
            ])
            // commentaire temporaire pour tester les regex,
            // sera récupérer du controller et ajouter, donc pas de sécurisation (champ non exposer)
            // ->add('author_id')
            ->add('cuisson_id', EntityType::class,[
                'class' => Cuisson::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' =>'name',
                'label' =>'Type de cuisson'
            ])
            ->add('alimentation_id', EntityType::class,[
                'class' => Alimentation::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' =>'name',
                'label' => 'Type d\'alimentation'
            ])
            ->add('plats_id', EntityType::class,[
                'class' => Plats::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' =>'name',
                'label' => 'Type de plats'
            ])
            // ->add('users_fav_id')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
