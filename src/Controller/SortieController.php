<?php

namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\AnnulerSortieType;
use App\Form\RechercheSortieType;
use App\Form\SortieFormType;
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
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sorties= $sortieRepo->findAllSorties();
        dump($sorties);
       if ($form->isSubmitted()) {
            $searchParameters= $form->getData();
            dump($searchParameters);

           $sorties= $sortieRepo->findSortieParametre($user,$searchParameters);
           dump($sorties);
       }

        return $this->render('sortie/recherche.html.twig', [
        'rechercheSortieForm'=>$form->createView(),
        'sorties'=>$sorties
        ]);
    }

    /**
     * @Route("/sortie/creer", name="sortie_creer")
     */
    public function create(Request $request) :Response
    {
        $sortie = new Sortie();
        $organisateur=$this->getUser();

        $form=$this->createForm(SortieFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $sortie=$form->getData();

            $sortie->setOrganisateur($organisateur);
            $sortie->setCampus($organisateur->getRattacheA());
            $repoEtat=$this->getDoctrine()->getRepository(Etat::class);
            if($form->get('enregistrer')->isClicked()) {
                $etat = $repoEtat->findOneByLibelle('En création');
            }
            if($form->get('publier')->isClicked()){
                $etat = $repoEtat->findOneByLibelle('Ouverte');
            }
            $sortie->setEtat($etat);
            $em = $this->getDoctrine()->getManager();
            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute('sortie_recherche');

        }


        return $this->render('sortie/creerSortie.html.twig', [
            'controller_name' => 'SortieController',
            'creerSortie'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/sortie/detail/{id}", name="sortie_afficher")
     */
    public function afficher($id)
    {
        $repo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortieInfos = $repo->find($id);
        dump($sortieInfos);

        return $this->render('sortie/detailSortie.html.twig', [
            'controller_name' => 'SortieController',
            'sortieInfos'=>$sortieInfos,
        ]);
    }

    /**
     * @Route("/sortie/modifier/{id}", name="sortie_modifier")
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
    public function annuler($id, Request $request) :Response
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortieInfos= $sortieRepo->find($id);

        $form=$this->createForm(AnnulerSortieType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $sortieInfos->setMotifAnnulation($sortie=$form->getData()->getMotifAnnulation());
            $majInfo = $sortieRepo->findOneById($sortieInfos->getId());

            $em = $this->getDoctrine()->getManager();
            $em->persist($majInfo);
            $em->flush();
            $this->addFlash('success','La sortie a été annulée !');
            return $this->redirectToRoute('sortie_recherche');
        }

        return $this->render('sortie/annulerSortie.html.twig', [
            'controller_name' => 'SortieController',
            'annulerSortie'=>$form->createView(),
            'sortieInfos'=>$sortieInfos,
        ]);
    }
}
