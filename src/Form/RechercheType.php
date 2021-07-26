<?php

namespace App\Form;

use App\Entity\Alimentation;
use App\Entity\Cuisson;
use App\Entity\Plats;
use App\Entity\Recette;
use App\Entity\Ingredients;
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

class RechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
     {
         $builder
             ->add ('niveau', TextType::class,[
                'required' => false,
             ])

             ->add ('plats', EntityType::class,[
                'class' => Plats::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' =>'name',     
                'placeholder' => 'Ne pas rechercher',
                'required' => false,

                'label' => 'Plats' ])

             ->add ('recette', TextType::class,[
                 'required' => false,
             ])

             ->add ('alimentation', EntityType::class,[
                'class' => Alimentation::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' =>'name',   
                'placeholder' => 'Ne pas rechercher',
                'required' => false,          
                'label' => 'Alimentation' 
             ])

             ->add ('ingredient', EntityType::class,[
                 'class' => Ingredients::class,
                 'multiple' => false,
                 'expanded' => false,
                 'choice_label' =>'name',    
                 'placeholder' => 'Ne pas rechercher',
                'required' => false,         
                 'label' => 'Ingrédient' ])
                
             ->add ('cuisson', EntityType::class,[
                'class' => Cuisson::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' =>'name', 
                'placeholder' => 'Ne pas rechercher',
                'required' => false,            
                'label' => 'Cuisson' ])
        
     ;}

    //  public function configureOptions(OptionsResolver $resolver) {
    //      $resolver->setDefaults([
    //          'data_class' => Recette::class,
    //      ]);
    //  }

 }
