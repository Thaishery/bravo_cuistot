<?php

namespace App\Form;

use App\Entity\Ingredients;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class IngredientsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un nom.'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-ZàâäêéèëîïôöùûüÀÂÄÊËÎÏÔÖÙÛÜŒœÇç]/',
                        'message' => 'Ce champ ne peut contenir que des caractéres alphabétiques, accentuation incluse.'
                    ])
                ],
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ingredients::class,
        ]);
    }
}
