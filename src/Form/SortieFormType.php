<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $aujourdhui = new \DateTime();
        $aujourdhui ->modify('+ 2 hours');
        $builder
            ->add('nom')
            ->add('dateHeureDebut', DateTimeType::class, [

                'data' => $aujourdhui,

                ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'data' => $aujourdhui,
            ])

            ->add('nbInscriptionsMax')
            ->add('duree')
            ->add('infosSortie', TextareaType::class)
            ->add('campus',TextType::class,[
                'mapped'=>false,
                'disabled'=> true,
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nom',
                'mapped' => false,
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
            ->add('enregistrer',SubmitType::class, [
                'attr'=>['class'=>'btn-primary col']
            ])
            ->add('publier',SubmitType::class, [
                'attr'=>['class'=>'btn-primary col']
            ])
            ->add('annuler',ButtonType::class,[
                'attr'=>['onclick'=>'location.href="/sortir/public/"', 'class'=>'btn-primary col']
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
