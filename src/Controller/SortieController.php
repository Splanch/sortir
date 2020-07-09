<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Form\RechercheSortieType;
use App\Form\SortieFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/", name="sortie_recherche")
     */
    public function recherche()
    {
        return $this->render('sortie/recherche.html.twig', [
            'controller_name' => 'SortieController',
            ]);
    }

    /**
     * @Route("/sortie/creer", name="sortie_creer")
     */
    public function create()
    {
        $sortie=new Sortie();
        $organisateur = $this->getUser();
        $sortie->setOrganisateur($organisateur);
        $sortie->setCampus($organisateur->getRattacheA());

        $form = $this->createForm(SortieFormType::class,$sortie);

        return $this->render('sortie/creerSortie.html.twig', [
            'controller_name' => 'SortieController',
            'creerSortie'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/sortie/detail/{id}", name="sortie_afficher")
     */
    public function afficher()
    {
        return $this->render('sortie/afficher.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    /**
     * @Route("/sortie/{id}", name="sortie_modifier")
     */
    public function modifier()
    {
        return $this->render('sortie/sortie.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    /**
     * @Route("/sortie/cancel/{id}", name="sortie_annuler")
     */
    public function annuler()
    {
        return $this->render('sortie/annuler.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }
}
