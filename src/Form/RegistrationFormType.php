<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use function MongoDB\Driver\Monitoring\removeSubscriber;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//Formbuilder différent selon profil admin ou user

        $builder
            ->add('pseudo')
            ->add('prenom')
            ->add('nom')
            ->add('telephone')
            ->add('email')
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez rentrer un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit avoir {{ limit }} characters minimum',
                        // max length allowed by Symfony for security reasons
                        'max' => 20,
                        'maxMessage' => 'Le mot de passe ne doit pas dépasser {{ limit }} characters maximum',
                    ]),
                ],
            ])
            ->add('password',RepeatedType::class,[
                'type'=>PasswordType::class,
                'mapped'=>false,
                'invalid_message' => 'Veuillez rentrer deux fois le même mot de passe.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez rentrer un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit avoir {{ limit }} characters minimum',
                        // max length allowed by Symfony for security reasons
                        'max' => 20,
                        'maxMessage' => 'Le mot de passe ne doit pas dépasser {{ limit }} characters maximum',
                    ]),
                ],
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation'],
            ]);



        if($options['userConnecte']->getRoles()[0] == 'ROLE_ADMIN'){
            $builder

                ->add('administrateur')
                ->add('actif')
                ->add('rattacheA',EntityType::class,[
                    'class'=>Campus::class,
                    'choice_label'=> function(Campus $campus){
                        return $campus->getNom();
                    }
                ])   ;
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
            'userConnecte' => null,
        ]);
    }
}
