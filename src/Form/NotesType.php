<?php

namespace App\Form;

use App\Entity\Notes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class NotesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('note', NumberType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ajouter une note.'
                    ]),
                    //1er caractére est dif de 0 (marche aussi avec 00 car symfony considére 00 comme 0) : 
                    new Assert\Regex([
                        'pattern' => '/^0{1}/',
                        'match' => false,
                        'message' => 'ce champ ne peut être vide'
                    ]),
                    //au maximum 1 caractéres
                    // new Assert\Regex([
                    //     'pattern' => '/^.{1,}/',
                    //     'match' => false,
                    //     'message' => 'Ce champ peut contenir au maximum 1 charactéres'
                    // ]),
                    //comprit entre 1 et 5
                    new Assert\Regex([
                        'pattern' => '/^[1-5]/',
                        'message' => 'la note ne peut être comprise qu\'entre 1 et 5 '
                    ])
                    //uniquement des chiffre (le NumberType s'en charge deja): 
                    ],
                'invalid_message'=>'Ce champ peut contenir uniquement un nombre, comprit entre 1 et 5',
                'required' => true,
                ], 
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Notes::class,
        ]);
    }
}
