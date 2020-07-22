<?php

namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\AnnulerSortieType;
use App\Form\RechercheSortieType;
use App\Form\SortieFormType;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class SortieController extends AbstractController
{
    /**
     * @Route("/", name="sortie_recherche")
     */
    public function recherche(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(RechercheSortieType::class);
        $form->handleRequest($request);
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sorties = $sortieRepo->findAllSorties();
        $date = new DateTime();
        $date->setTimezone(new \DateTimeZone('Europe/Paris'));
        foreach ($sorties as $sortie) {
            $dateDeFinSortie = clone $sortie->getDateHeureDebut();
            $dateDeFinSortie->modify('+' . $sortie->getDuree() . 'minutes');
            $etat = new Etat();

            if ($sortie->getEtat()->getLibelle() == 'Historisée'
                or $sortie->getEtat()->getLibelle() == 'En Création'
                or $sortie->getEtat()->getLibelle() == 'Annulée') {
                break;
            } else {
                if ($date > $sortie->getDateHeureDebut() and $date < $dateDeFinSortie) {
                    $etat->setLibelle('En Cours');
                    $sortie->setEtat($etat);
                    break;
                } elseif ($dateDeFinSortie < $date) {
                    $etat->setLibelle('Terminée');
                    $sortie->setEtat($etat);
                    break;
                } elseif ((sizeof($sortie->getParticipants()) == $sortie->getNbInscriptionsMax()) or $date > $sortie->getdateLimiteInscription()) {
                    $etat->setLibelle('Clôturée');
                    $sortie->setEtat($etat);
                    break;
                }

            }
        }
        if ($form->isSubmitted()) {
            $searchParameters = $form->getData();
            $sorties = $sortieRepo->findSortieParametre($user, $searchParameters);

        }
        return $this->render('sortie/recherche.html.twig', [
            'rechercheSortieForm' => $form->createView(),
            'sorties' => $sorties
        ]);
    }

    /**
     * @Route("/sortie/creer", name="sortie_creer")
     */
    public function create(Request $request): Response
    {

        $organisateur = $this->getUser();

        $form = $this->createForm(SortieFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $sortie = $form->getData();

            $sortie->setOrganisateur($organisateur);
            $sortie->setCampus($organisateur->getRattacheA());
            $repoEtat = $this->getDoctrine()->getRepository(Etat::class);
            if ($form->get('enregistrer')->isClicked()) {
                $etat = $repoEtat->findOneByLibelle('En création');
            }
            if ($form->get('publier')->isClicked()) {
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
            'creerSortie' => $form->createView(),
        ]);

    }

/***************************************** AJAX ***************************************************************/

    /**
     * @Route("/sortie/ajax/choixville")
     */
    public function choixville(Request $request) {
        $lieux = $this
            ->getDoctrine()
            ->getRepository(Lieu::class)
            ->findByVille($request->get('villeval'));

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $jsonData = array();
            $idx = 0;
            foreach($lieux as $lieu) {

                $temp = array(
                    'id' => $lieu->getId(),
                    'nom' => $lieu->getNom(),
                );
                $jsonData[$idx++] = $temp;
            }
            return new JsonResponse($jsonData);
        } else {
            return $this->render('sortie/creerSortie.html.twig');
        }
    }

    /**
     * @Route("/sortie/ajax/cp")
     */
    public function recuperationcp(Request $request) {
        $cps = $this
            ->getDoctrine()
            ->getRepository(Ville::class)
            ->findById($request->get('villeval'));

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $jsonData = array();
            $idx = 0;
            foreach($cps as $cp) {

                $temp = array(
                    'cp' => $cp->getCodePostal(),
                );
                $jsonData[$idx++] = $temp;
            }

            return new JsonResponse($jsonData);
        } else {
            return $this->render('sortie/creerSortie.html.twig');
        }
    }

    /**
     * @Route("/sortie/ajax/geoloc")
     */
    public function recuperationgeoloc(Request $request) {
        $geolocs = $this
            ->getDoctrine()
            ->getRepository(Lieu::class)
            ->findById($request->get('idlieu'));

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $jsonData = array();
            $idx = 0;
            foreach($geolocs as $geoloc) {

                $temp = array(
                    'rue' => $geoloc->getRue(),
                    'lat' => $geoloc->getLatitude(),
                    'long' => $geoloc->getLongitude(),
                );
                $jsonData[$idx++] = $temp;
            }

            return new JsonResponse($jsonData);
        } else {
            return $this->render('sortie/creerSortie.html.twig');
        }
    }

    /***************************************** FIN AJAX ***************************************************************/

    /**
     * @Route("/sortie/detail/{id}", name="sortie_afficher")
     */
    public function afficher($id)
    {
        $repo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortieInfos = $repo->find($id);
        return $this->render('sortie/detailSortie.html.twig', [
            'controller_name' => 'SortieController',
            'sortieInfos' => $sortieInfos,
        ]);
    }

    /**
     * @Route("/sortie/modifier/{id}", name="sortie_modifier")
     */
    public function modifier($id, UserInterface $user, Request $request) :Response
    {
        if (!$user) {
            return $this->render('security/login.html.twig');
        }


        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie= $sortieRepo->find($id);

        $form = $this->createForm(SortieFormType::class, $sortie);
        $form->handleRequest($request);

        $organisateur = $sortie->getOrganisateur();
        $etat = $sortie->getEtat();
//       état "En création"
        $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
        $etatEnCreation = $etatRepo->findOneByLibelle('En création');
//        état "Ouverte"
        $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
        $etatOuvert = $etatRepo->findOneByLibelle('Ouverte');

        if($user!=$organisateur or $etat!=$etatEnCreation){
            return $this->redirectToRoute('sortie_recherche');
        }

        $dateSortie = $sortie->getDateHeureDebut();
        $dateInscription = $sortie->getDateLimiteInscription();
        $dateActuelle = new DateTime();


        if ($form->isSubmitted() && $form->isValid()) {
            if ($dateInscription > $dateActuelle && $dateInscription <= $dateSortie) {

                if ($form->get('enregistrer')->isClicked()) {
                    $this->addFlash('success', 'Vos modifications ont été enregistrées !');
                }
                if ($form->get('publier')->isClicked()) {
                    $sortie->setEtat($etatOuvert);
                    $this->addFlash('success', 'Votre sortie a été publiée !');
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($sortie);
                $em->flush();
                return $this->redirectToRoute('sortie_recherche');

            }else {
                $this->addFlash('warning', 'Veuillez modifiez les dates !');
            }

        }

        return $this->render('sortie/modifierSortie.html.twig', [
            'controller_name' => 'SortieController',
            'modifierSortie' => $form->createView()
        ]);
    }

    /**
     * @Route("/sortie/annuler/{id}", name="sortie_annuler")
     */
    public function annuler($id, Request $request): Response
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortieInfos = $sortieRepo->find($id);
//        recuperation d'état "Annulée"
        $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
        $annulee = $etatRepo->findOneByLibelle('Annulée');

        $form = $this->createForm(AnnulerSortieType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sortieInfos->setMotifAnnulation($sortie = $form->getData()->getMotifAnnulation());
            $sortieInfos->setEtat($annulee);
            $majInfo = $sortieRepo->findOneById($sortieInfos->getId());

            $em = $this->getDoctrine()->getManager();
            $em->persist($majInfo);
            $em->flush();
            $this->addFlash('success', 'La sortie a été annulée !');
            return $this->redirectToRoute('sortie_recherche');
        }

        return $this->render('sortie/annulerSortie.html.twig', [
            'controller_name' => 'SortieController',
            'annulerSortie' => $form->createView(),
            'sortieInfos' => $sortieInfos,
        ]);
    }

    /**
     * @Route("sortie/inscrire/{id}", name="inscrire")
     */
    public function inscrire($id, UserInterface $user, EntityManagerInterface $manager)
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        $sortie->addParticipant($user);
        $manager->persist($sortie);
        $manager->flush();
        $this->addFlash('success', 'Vous êtes inscrit !');
        return $this->redirectToRoute('sortie_recherche');

    }

    /**
     * @Route("sortie/desister/{id}", name="desister")
     */
    public function desister($id, UserInterface $user, EntityManagerInterface $manager)
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        $sortie->removeParticipant($user);
        $manager->persist($sortie);
        $manager->flush();
        $this->addFlash('success', 'Vous vous êtes désisté !');
        return $this->redirectToRoute('sortie_recherche');
    }

    /**
     * @Route("sortie/publier/{id}", name="publier")
     */
    public function publier($id, UserInterface $user, EntityManagerInterface $manager)
    {
        if (!$user) {
            return $this->render('security/login.html.twig');
        }

        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        $organisateur = $sortie->getOrganisateur();
        $etat = $sortie->getEtat();
//       état "En création"
        $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
        $etatEnCreation = $etatRepo->findOneByLibelle('En création');
//        état "Ouverte"
        $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
        $etatOuvert = $etatRepo->findOneByLibelle('Ouverte');

        if($user!=$organisateur or $etat!=$etatEnCreation){
            return $this->redirectToRoute('sortie_recherche');
        }

        $dateSortie = $sortie->getDateHeureDebut();
        $dateInscription = $sortie->getDateLimiteInscription();
        $dateActuelle = new DateTime();

        if ($dateInscription > $dateActuelle && $dateInscription <= $dateSortie) {
            $sortie->setEtat($etatOuvert);
            $manager->persist($sortie);
            $manager->flush();
            $this->addFlash('success', 'Votre sortie a été publiée !');
            return $this->redirectToRoute('sortie_recherche');

        } else {
            $this->addFlash('warning', 'Veuillez modifiez les dates !');
            return $this->redirectToRoute('sortie_recherche');

        }




    }
}
