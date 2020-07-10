<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut')
            ->add('dateLimiteInscription')
            ->add('nbInscriptionsMax')
            ->add('duree')
            ->add('infosSortie', TextareaType::class)
            ->add('campus',TextType::class,[
                'mapped'=>false,
                'disabled'=> true,
            ])
            ->add('ville',EntityType::class,[
                'class'=>Ville::class,
                'mapped'=>false,
                'label'=>'Ville',
                'choice_label'=> function(Ville $ville){
                    return $ville->getNom();

                }
            ])
            ->add('lieu',EntityType::class,[
                'class'=>Lieu::class,
                'label'=>'Lieu',
                'choice_label'=> function(Lieu $lieu){
                    return $lieu->getNom();
                }
            ])
            ->add('rue',EntityType::class,[
                'class'=>Lieu::class,
                'mapped'=>false,
                'label'=>'Rue',
                'choice_label'=> function(Lieu $lieu){
                    return $lieu->getRue();

                }
            ])
            ->add('codePostal',EntityType::class,[
                'class'=>Lieu::class,
                'mapped'=>false,
                'label'=>'Code Postal',
                'choice_label'=> function(Lieu $lieu){
                    return $lieu->getVille()->getCodePostal();
                }
                ])

            ->add('latitude',TextType::class,[
                'mapped'=>false,
            ])
            ->add('longitude',TextType::class,[
                'mapped'=>false,
            ])
            ->add('enregistrer',SubmitType::class)
            ->add('publier',SubmitType::class)
            ->add('annuler',ButtonType::class,[
                'attr'=>['onclick'=>'location.href="/sortir/public/"']
            ])
        ;



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'user'=>null,
        ]);
    }
}
