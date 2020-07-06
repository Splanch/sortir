<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SortirController extends AbstractController
{

    /**
     * @Route("/sortie/creer", name="creation")
     */
    public function create()
    {
        return $this->render('sortir/creer.html.twig', [
            'controller_name' => 'SortirController',
        ]);
    }

    /**
     * @Route("/sortie/detail/{id}", name="afficher")
     */
    public function afficher()
    {
        return $this->render('sortir/sortie.html.twig', [
            'controller_name' => 'SortirController',
        ]);
    }

    /**
     * @Route("/sortie/{id}", name="modifier")
     */
    public function modifier()
    {
        return $this->render('sortir/sortie.html.twig', [
            'controller_name' => 'SortirController',
        ]);
    }

    /**
     * @Route("/sortie/cancel/{id}", name="annuler")
     */
    public function annuler()
    {
        return $this->render('sortir/sortie.html.twig', [
            'controller_name' => 'SortirController',
        ]);
    }
}
