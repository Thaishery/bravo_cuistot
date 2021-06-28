<?php

namespace App\Form;

use App\Entity\Recette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;
use Webmozart\Assert\Assert as AssertAssert;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un nom de recette.'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-ZàâäêéèëîïôöùûüÀÂÄÊËÎÏÔÖÙÛÜŒœÇç]/',
                        'message' => 'Ce champ ne peut contenir que des caractéres alphabétiques, accentuation incluse.',
                    ])
                ]
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
                        'message' => 'Veuillez entrer un temps de préparation'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]{0,3}/',
                        'message' => 'Ce champ ne peut contenir que des caractéres numériques (maximum 4) et est exprimé en minutes. max 999 minutes'
                    ])
                ]
            ])
            ->add('temps_cuisson', NumberType::class,[
                'constraints' =>[
                    new NotBlank([
                        'message' => 'Veuillez entrer un temps de cuisson, mettez 0 si votre recette ne necessite pas de cuisson.'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]{0,4}/',
                        'message' => 'Ce champ ne peut contenir que des caractéres numériques (maximum 3) et est exprimé en minutes. max 9999 minutes'
                    ])
                ]
            ])
            ->add('nb_personnes', NumberType::class,[
                'constraints' =>[
                    new NotBlank([
                        'message' => 'Veuillez entrer le nombre de pars (pers.).'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]{0,2}/',
                        'message' => 'Ce champ ne peut contenir que des caractéres numériques (maximum 2). max 99 personnes'
                    ])
                ]
            ])
            ->add('difficulte', NumberType::class,[
                'constraints' =>[
                    new NotBlank([
                        'message' => 'Veuillez entrer une difficulté.'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/[1-3]{1}/',
                        'message' => 'Ce champ ne peut varier que de 1 a 3.'
                    ])
                ]
            ])
            // commentaire temporaire pour tester les regex,
            // sera récupérer du controller et ajouter, donc pas de sécurisation (champ non exposer)
            // ->add('author_id')
            // ->add('cuisson_id')
            // ->add('alimentation_id')
            // ->add('plats_id')
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
