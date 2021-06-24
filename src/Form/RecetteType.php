<?php

namespace App\Form;

use App\Entity\Recette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('image')
            ->add('temps_preparation')
            ->add('temps_cuisson')
            ->add('nb_personnes')
            ->add('difficulte')
            ->add('author_id')
            ->add('cuisson_id')
            ->add('alimentation_id')
            ->add('plats_id')
            ->add('users_fav_id')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
