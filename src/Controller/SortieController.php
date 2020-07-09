<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Form\RechercheSortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/", name="sortie_recherche")
     */
    public function recherche(Request $request):Response
    {
        $user=$this-> getUser();
        if(!$user){
            return $this-> redirectToRoute('app_login');
        }
        $form=$this->createForm(RechercheSortieType::class);
        $form->handleRequest($request);
       if ($form->isSubmitted()) {
            $searchParameters= $form->getData();
            dump($searchParameters);

           $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
           $sorties= $sortieRepo->findSortieParametre($user,$searchParameters);
           dump($sorties);
       }

        return $this->render('sortie/recherche.html.twig', [
        'rechercheSortieForm'=>$form->createView(),
        //'sorties'=>$sorties
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
