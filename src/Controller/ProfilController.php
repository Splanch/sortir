<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{

    /**
     * @Route("/recherche", name="recherche")
     */
    public function recherche()
    {
        return $this->render('/accueil.html.twig', [
            'controller_name' => 'Controller',
        ]);
    }

    /**
     * @Route("/profil/{id}", name="profil")
     */
    public function monprofil()
    {
        return $this->render('/profil.html.twig', [
            'controller_name' => 'Controller',
        ]);
    }

    /**
     * @Route("/profil/detail/{id}", name="afficher_profil")
     */
    public function afficherprofil()
    {
        return $this->render('/profil.html.twig', [
            'controller_name' => 'Controller',
        ]);
    }


}
