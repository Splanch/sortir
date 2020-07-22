<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
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
            ->add('ville', EntityType::class,[
                'class' => Ville::class,
                'choice_label' => 'nom',
                'placeholder' => 'Faire votre choix',
                'attr' => [
                    'class' => 'listevilles'
                ]
            ])
            ->add('lieu',ChoiceType::class,[
                'placeholder' => 'Choisir une ville',
                'attr' => [
                    'class' => 'listelieux'
                ]
            ])
            ->add('rue',TextType::class,[
                'disabled' => true,
                'attr' => [
                    'class' => 'inforue'
                ]
            ])
            ->add('codePostal',TextType::class,[
                'disabled' => true,
                'attr' => [
                    'class' => 'infocp'
                ]
            ])
            ->add('latitude',TextType::class,[
                'disabled' => true,
                'attr' => [
                    'class' => 'infolat'
                ]
            ])
            ->add('longitude',TextType::class,[
                'disabled' => true,
                'attr' => [
                    'class' => 'infolong'
                ]
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
