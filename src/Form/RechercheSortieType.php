<?php

namespace App\Form;

use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'class'=>Campus::class,
                'choice_label'=>function(Campus $campus){
                return $campus->getNom();
                }
            ])
            ->add('keywords', SearchType::class,[
                'label'=> 'Le nom de la sortie contient : '
            ])
            ->add('dateDebut',DateType::class,[
                'format'=>'dd MM yyyy',
                'label'=> 'Entre'
            ])
            ->add('dateFin',DateType::class,[
                'format'=>'dd MM yyyy',
                'label'=> 'et'
            ])
            ->add('organiseesParMoi', CheckboxType::class,[
                'label'=> 'Sorties organisées par moi'
            ])
            ->add('jeSuisInscrit', CheckboxType::class,[
                'label'=> 'Sorties auxquelles je suis inscrit/e'
            ])
            ->add('nonInscrit', CheckboxType::class,[
                'label'=> 'Sorties auxquelles je ne suis pas inscrit/e'
            ])
            ->add('sortiesPassees', CheckboxType::class,[
                'label'=> 'Sorties passées'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
