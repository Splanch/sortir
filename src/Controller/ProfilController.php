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
    public function monProfil($id, $affichage = false, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $repo = $this->getDoctrine()->getRepository(Participant::class);
        $userConnecte = $this->getUser();
        $userProfil=$repo->find($id);

        if ($userConnecte!=$userProfil) {
            return $this->redirectToRoute('sortie_recherche');
        }else {
                $form = $this->createForm(RegistrationFormType::class, $userProfil, ['userConnecte' => $this->getUser()]);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    // encode the plain password
                    $userConnecte->setPassword(
                        $passwordEncoder->encodePassword(
                            $userProfil,
                            $form->get('password')->getData()
                        )
                    );

                    if ($form->getData()->getAdministrateur()) {
                        $userProfil->setRoles(array('ROLE_ADMIN'));
                    } else {
                        $userProfil->setRoles(array('ROLE_USER'));
                    }

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($userProfil);
                    $entityManager->flush();
                    $this->addFlash('success', 'Votre profil a été modifié.');
                    return $this->redirectToRoute('sortie_recherche');
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
        public
        function afficherprofil($id, $affichage = true, Request $request)
        {
            $repo = $this->getDoctrine()->getRepository(Participant::class);
            $user = $repo->find($id);

            return $this->render('profil/profil.html.twig', [
                'user' => $user,
                'affichage' => $affichage,
            ]);

        }


    }
