<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{



    /**
     * @Route("/profil/{id}", name="profil_mon_profil")
     */
    public function monprofil($id,Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $repo = $this->getDoctrine()->getRepository(Participant::class);
        $user= $repo->find($id);
        if(!$user){
            return $this->render('security/login.html.twig',[]);
        }else {
            $form = $this->createForm(RegistrationFormType::class, $user,['userConnecte'=>$this->getUser()]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

                if($form->getData()->getAdministrateur())
                {
                    $user->setRoles(array('ROLE_ADMIN'));
                } else {
                    $user->setRoles(array('ROLE_USER'));
                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success','Votre profil a été modifié.');

           }
        }
        return $this->render('profil/profil.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profil/detail/{id}", name="profil_afficher")
     */
    public function afficherprofil()
    {
        return $this->render('profil/profil.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }


}
