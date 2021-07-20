<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class RemoveFavoriType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enleverFavori', SubmitType::class, [
                'validation_groups' => false, //dit au form de ne pas faire le submit de l'autre formulaire
                'label'=>'enlever des favoris',
                'attr'=>[
                    'class'=>'form-control'
                ],

            ]);
    }
}