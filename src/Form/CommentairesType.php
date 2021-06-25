<?php

namespace App\Form;

use App\Entity\Commentaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class CommentairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre commentaire ne peut pas être vide.'
                    ]),
                    new Assert\Regex([
                        //this field may be exposed to the user, so we may want to secure it.
                        //sql injection prevention: this should work (using # # to escape the regex as \ would throw error. )
                        'pattern' => '#^(?:[\(\'\{"\$\\\/\[\]\}]){2}#', 
                        'match' => false,
                        'message' => 'Ce champ ne peut contenir que des caractéres alphabétiques, accentuation incluse.'
                    ])
                ],
                'required' => true,
            ])
            ->add('created_at')
            ->add('edited_at')
            // ->add('user_id')
            // ->add('recette_id')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commentaires::class,
        ]);
    }
}
