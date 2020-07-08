<?php

namespace App\Controller;


use App\Form\RechercheSortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/", name="sortie_recherche")
     */
    public function recherche(Request $request)
    {
        $user=$this-> getUser();
        if(!$user){
            return $this-> redirectToRoute('app_login');
        }
        $form=$this->createForm(RechercheSortieType::class);




        return $this->render('sortie/recherche.html.twig', [
        'rechercheSortieForm'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/sortie/creer", name="sortie_creer")
     */
    public function create()
    {
        return $this->render('sortie/sortie.html.twig', [
            'controller_name' => 'SortieController',
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
