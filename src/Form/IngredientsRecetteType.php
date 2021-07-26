<?php

namespace App\Form;

use App\Entity\IngredientsRecette;
use App\Entity\Ingredients;
use App\Entity\UniteMesure;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class IngredientsRecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ingredients_id', EntityType::class,[
                'class' => Ingredients::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' =>'name',
                'label' => 'Ingredient : '
            ])
            ->add('quantite', NumberType::class,[
                'constraints' =>[
                    new NotBlank([
                        'message' => 'Veuillez entrer une quantité',
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
                'invalid_message'=>'Ce champ peut contenir uniquement un nombre, comprit entre 1 et 999',
                'label' => 'Quantitée : '
            ])
            ->add('unitemesure_id', EntityType::class,[
                'class' => UniteMesure::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' =>'name',
                'label' => 'unitée de mesure : '
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IngredientsRecette::class,
        ]);
    }
}
