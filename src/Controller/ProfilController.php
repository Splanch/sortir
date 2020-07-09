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
    public function monprofil($id,$affichage = false, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $repo = $this->getDoctrine()->getRepository(Participant::class);
        $user= $repo->find($id);
        if(!$user){
            return $this->render('security/login.html.twig',[]);
        }else {
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success','Votre profil a été modifié.');
           }
        }
        return $this->render('profil/profil.html.twig', [
            'registrationForm' => $form->createView(),
            'affichage' => $affichage,
        ]);
    }

    /**
     * @Route("/profil/detail/{id}", name="profil_afficher")
     */
    public function afficherprofil($id,$affichage = true, Request $request)
    {

        $repo = $this->getDoctrine()->getRepository(Participant::class);
        $user = $repo->find($id);


        return $this->render('profil/profil.html.twig', [
            'user' => $user,
            'affichage' => $affichage,
        ]);

    }


}
