<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->passwordEncoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {


        $faker = Faker\Factory::create('fr_FR');

        $campus1 = new Campus();
        $campus1->setNom('Rennes');
        $manager->persist($campus1);

        $campus2 = new Campus();
        $campus2->setNom('Nantes');
        $manager->persist($campus2);

        $campus3 = new Campus();
        $campus3->setNom('Niort');
        $manager->persist($campus3);
        $manager->flush();

        $allCampus = array($campus1, $campus2, $campus3);

        $ville1 = new Ville();
        $ville1->setNom('Paris');
        $ville1->setCodePostal('75000');
        $manager->persist($ville1);

        $ville2 = new Ville();
        $ville2->setNom('Lyon');
        $ville2->setCodePostal('69000');
        $manager->persist($ville2);

        $ville3 = new Ville();
        $ville3->setNom('Marseille');
        $ville3->setCodePostal('13000');
        $manager->persist($ville3);
        $manager->flush();

        $allVilles = array($ville1, $ville2, $ville3);

        $lieu1 = new Lieu();
        $lieu1->setNom('Tour Eiffel');
        $lieu1->setRue('Champ de Mars, 5 Avenue Anatole France');
        $lieu1->setLongitude('2.294481');
        $lieu1->setLatitude('48.858370');
        $lieu1->setVille($ville1);
        $manager->persist($lieu1);

        $lieu2 = new Lieu();
        $lieu2->setNom('Le Louvre');
        $lieu2->setRue('Rue de Rivoli');
        $lieu2->setLongitude('2.334595');
        $lieu2->setLatitude('48.864824');
        $lieu2->setVille($ville1);
        $manager->persist($lieu2);

        $lieu3 = new Lieu();
        $lieu3->setNom('Arc de Triomphe');
        $lieu3->setRue('Place Charles de Gaulle');
        $lieu3->setLongitude('2.295');
        $lieu3->setLatitude('48.8738');
        $lieu3->setVille($ville1);
        $manager->persist($lieu3);
        $manager->flush();

        $lieu4 = new Lieu();
        $lieu4->setNom('Basilique de Fourvière');
        $lieu4->setRue('8 Place de Fourvière');
        $lieu4->setLongitude('4.819929');
        $lieu4->setLatitude('45.760683');
        $lieu4->setVille($ville2);
        $manager->persist($lieu4);

        $lieu5 = new Lieu();
        $lieu5->setNom('Parc de la Tête d\'Or');
        $lieu5->setRue('Boulevard de Stalingrad');
        $lieu5->setLongitude('45.75');
        $lieu5->setLatitude('4.85');
        $lieu5->setVille($ville2);
        $manager->persist($lieu5);

        $lieu6 = new Lieu();
        $lieu6->setNom('Théâtres romains');
        $lieu6->setRue('Rue de l\'Antiquaille');
        $lieu6->setLongitude('4.820088');
        $lieu6->setLatitude('45.760703');
        $lieu6->setVille($ville2);
        $manager->persist($lieu6);
        $manager->flush();

        $lieu7 = new Lieu();
        $lieu7->setNom('Vieux-Port');
        $lieu7->setRue('Quai des Belges');
        $lieu7->setLongitude('5.374298');
        $lieu7->setLatitude('43.295160');
        $lieu7->setVille($ville3);
        $manager->persist($lieu7);

        $lieu8 = new Lieu();
        $lieu8->setNom('Basilique Notre-Dame de la Garde');
        $lieu8->setRue('Rue Fort du Sanctuaire');
        $lieu8->setLongitude('5.371234');
        $lieu8->setLatitude('43.283961');
        $lieu8->setVille($ville3);
        $manager->persist($lieu8);

        $lieu9 = new Lieu();
        $lieu9->setNom('Château d\'If');
        $lieu9->setRue('Ile d\'If');
        $lieu9->setLongitude('5.325958');
        $lieu9->setLatitude('43.280213');
        $lieu9->setVille($ville3);
        $manager->persist($lieu9);
        $manager->flush();
        $allLieux = array($lieu1, $lieu2, $lieu3, $lieu4, $lieu5, $lieu6, $lieu7, $lieu8, $lieu9);
        $allParticipants = array();


        for ($i = 0; $i < 50; $i++) {
            $participant = new Participant();
            $participant->setNom($faker->lastName);
            $participant->setPrenom($faker->firstName);
            $participant->setTelephone($faker->phoneNumber);
            $participant->setEmail($faker->email);
            $hash = $this->passwordEncoder->encodePassword($participant, "password");
            $participant->setPassword($hash);
            $participant->setActif(True);
            if ($i % 2 === 0) {
                $participant->setRoles(array('ROLE_USER'));
                $participant->setAdministrateur(False);
            } else {
                $participant->setRoles(array('ROLE_ADMIN'));
                $participant->setAdministrateur(True);
            }
            $participant->setPseudo($faker->userName);
            $participant->setRattacheA($allCampus[rand(0, 2)]);
            $manager->persist($participant);
            $manager->flush();
            $allParticipants[$i] = $participant;
        }

        $creation = new Etat();
        $creation->setLibelle("En création");
        $manager->persist($creation);

        $ouverte = new Etat();
        $ouverte->setLibelle("Ouverte");
        $manager->persist($ouverte);

        $cloturee = new Etat();
        $cloturee->setLibelle("Clôturée");
        $manager->persist($cloturee);

        $enCours = new Etat();
        $enCours->setLibelle("En cours");
        $manager->persist($enCours);

        $terminee = new Etat();
        $terminee->setLibelle("Terminée");
        $manager->persist($terminee);

        $annulee = new Etat();
        $annulee->setLibelle("Annulée");
        $manager->persist($annulee);
        $manager->flush();

        $historisee = new Etat();
        $historisee->setLibelle("Historisée");
        $manager->persist($historisee);
        $manager->flush();

        $heureDebut = new DateTime();
        $finInscription = new DateTime();

        $sortie1 = new Sortie();
        $sortie1->setNom('Poterie en Deltaplane');
        $sortie1->setDateHeureDebut($heureDebut->modify('+10 day'));
        $sortie1->setDuree(180);
        $sortie1->setDateLimiteInscription($finInscription->modify('+8 day'));
        $sortie1->setNbInscriptionsMax(20);
        $sortie1->setInfosSortie('Prendre de la hauteur, admirer la vue, tout en fabriquant son vase en terre, rien de mieux pour se changer les idées [Sortie En Création]');
        $sortie1->setEtat($creation);
        $sortie1->setOrganisateur($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        $sortie1->setLieu($allLieux[rand(0, sizeof($allLieux) - 1)]);
        $sortie1->setCampus($allCampus[rand(0, sizeof($allCampus) - 1)]);
        $manager->persist($sortie1);
        $manager->flush();

        $sortie2 = new Sortie();
        $sortie2->setNom('Rock Party');
        $sortie2->setDateHeureDebut($heureDebut->modify('+12 day'));
        $sortie2->setDuree(120);
        $sortie2->setDateLimiteInscription($finInscription->modify('+7 day'));
        $sortie2->setNbInscriptionsMax(30);
        $sortie2->setInfosSortie('Tu aimes le jeu du Caillou, tu n\'a pas peur d\'affronter Cadock, alors viens danser et jetter des cailloux avec nous... Elle est où la poulette ?  [Sortie Ouverte pas d\'inscrits]');
        $sortie2->setEtat($ouverte);
        $sortie2->setOrganisateur($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        $sortie2->setLieu($allLieux[rand(0, sizeof($allLieux) - 1)]);
        $sortie2->setCampus($allCampus[rand(0, sizeof($allCampus) - 1)]);
        $manager->persist($sortie2);
        $manager->flush();

        $sortie3 = new Sortie();
        $sortie3->setNom('Aquaponie et Aquaponey');
        $sortie3->setDateHeureDebut($heureDebut->modify('+14 day'));
        $sortie3->setDuree(120);
        $sortie3->setDateLimiteInscription($finInscription->modify('+5 day'));
        $sortie3->setNbInscriptionsMax(25);
        $sortie3->setInfosSortie('Chevaucher les cheveux dans l\'étang entouré de bancs de poissons. Se reposer entre les cultures de salade et tomates. Viens tester avec nous l\'aquaponie en aquaponey [Sortie ouverte avec inscrits]');
        $sortie3->setEtat($ouverte);
        $sortie3->setOrganisateur($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        $sortie3->setLieu($allLieux[rand(0, sizeof($allLieux) - 1)]);
        $sortie3->setCampus($allCampus[rand(0, sizeof($allCampus) - 1)]);
        for ($i = 0; $i < 10; $i++) {
            $sortie3->addParticipant($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        }
        $manager->persist($sortie3);
        $manager->flush();

        $sortie4 = new Sortie();
        $sortie4->setNom('Ptit godet');
        $sortie4->setDateHeureDebut($heureDebut->modify('+5 day'));
        $sortie4->setDuree(180);
        $sortie4->setDateLimiteInscription($finInscription->modify('+4 day'));
        $sortie4->setNbInscriptionsMax(10);
        $sortie4->setInfosSortie('Boire un verre ou deux ou trois[Sortie clôturée car nombre max d\'inscrits atteint]');
        $sortie4->setEtat($cloturee);
        $sortie4->setOrganisateur($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        $sortie4->setLieu($allLieux[rand(0, sizeof($allLieux) - 1)]);
        $sortie4->setCampus($allCampus[rand(0, sizeof($allCampus) - 1)]);
        for ($i = 0; $i < 10; $i++) {
            $sortie4->addParticipant($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        }
        $manager->persist($sortie4);
        $manager->flush();

        $sortie5 = new Sortie();
        $sortie5->setNom('Partie de Loup Garous');
        $sortie5->setDateHeureDebut($heureDebut->modify('+1 day'));
        $sortie5->setDuree(180);
        $sortie5->setDateLimiteInscription($finInscription->modify('-1 day'));
        $sortie5->setNbInscriptionsMax(15);
        $sortie5->setInfosSortie('Thierecelieux est ton village préféré ? Viens on sera bien bien bien bien ! ');
        $sortie5->setEtat($cloturee);
        $sortie5->setOrganisateur($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        $sortie5->setLieu($allLieux[rand(0, sizeof($allLieux) - 1)]);
        $sortie5->setCampus($allCampus[rand(0, sizeof($allCampus) - 1)]);
        for ($i = 0; $i < 10; $i++) {
            $sortie5->addParticipant($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        }
        $manager->persist($sortie5);
        $manager->flush();

        $sortie6 = new Sortie();
        $sortie6->setNom('Sortie entre ennemis');
        $sortie6->setDateHeureDebut($heureDebut->modify('-1 hour'));
        $sortie6->setDuree(240);
        $sortie6->setDateLimiteInscription($finInscription->modify('-1 day'));
        $sortie6->setNbInscriptionsMax(17);
        $sortie6->setInfosSortie('Pour ceux qui déteste les autres, mais que sortir tout seul c\'est quand même moins fun[Sortie en cours]');
        $sortie6->setEtat($enCours);
        $sortie6->setOrganisateur($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        $sortie6->setLieu($allLieux[rand(0, sizeof($allLieux) - 1)]);
        $sortie6->setCampus($allCampus[rand(0, sizeof($allCampus) - 1)]);
        for ($i = 0; $i < 17; $i++) {
            $sortie6->addParticipant($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        }
        $manager->persist($sortie6);
        $manager->flush();

        $sortie7 = new Sortie();
        $sortie7->setNom('On va s\'coder ! ');
        $sortie7->setDateHeureDebut($heureDebut->modify('-15 day'));
        $sortie7->setDuree(240);
        $sortie7->setDateLimiteInscription($finInscription->modify('-18 day'));
        $sortie7->setNbInscriptionsMax(23);
        $sortie7->setInfosSortie('Sur un Mac ou sur un PC');
        $sortie7->setEtat($terminee);
        $sortie7->setOrganisateur($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        $sortie7->setLieu($allLieux[rand(0, sizeof($allLieux) - 1)]);
        $sortie7->setCampus($allCampus[rand(0, sizeof($allCampus) - 1)]);
        for ($i = 0; $i < 23; $i++) {
            $sortie7->addParticipant($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        }
        $manager->persist($sortie7);
        $manager->flush();

        $sortie8 = new Sortie();
        $sortie8->setNom('Sortie Archivée ');
        $sortie8->setDateHeureDebut($heureDebut->modify('-45 day'));
        $sortie8->setDuree(240);
        $sortie8->setDateLimiteInscription($finInscription->modify('-48 day'));
        $sortie8->setNbInscriptionsMax(12);
        $sortie8->setInfosSortie('Sortie archivée donc c\'est pas grave');
        $sortie8->setEtat($historisee);
        $sortie8->setOrganisateur($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        $sortie8->setLieu($allLieux[rand(0, sizeof($allLieux) - 1)]);
        $sortie8->setCampus($allCampus[rand(0, sizeof($allCampus) - 1)]);
        for ($i = 0; $i < 12; $i++) {
            $sortie8->addParticipant($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        }
        $manager->persist($sortie8);
        $manager->flush();

        $sortie9 = new Sortie();
        $sortie9->setNom('Aprèm Switch et Sandwich');
        $sortie9->setDateHeureDebut($heureDebut->modify('-20 day'));
        $sortie9->setDuree(240);
        $sortie9->setDateLimiteInscription($finInscription->modify('-23 day'));
        $sortie9->setNbInscriptionsMax(10);
        $sortie9->setInfosSortie('Manger et jouer à mario Kart, c\'est ça le bonheur');
        $sortie9->setEtat($annulee);
        $sortie9->setOrganisateur($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        $sortie9->setLieu($allLieux[rand(0, sizeof($allLieux) - 1)]);
        $sortie9->setCampus($allCampus[rand(0, sizeof($allCampus) - 1)]);
        for ($i = 0; $i < 10; $i++) {
            $sortie9->addParticipant($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        }
        $manager->persist($sortie9);
        $manager->flush();

        $sortie10 = new Sortie();
        $sortie10->setNom('Bal Masqué Ohé ohé - Soirée Covid');
        $sortie10->setDateHeureDebut($heureDebut->modify('-40 day'));
        $sortie10->setDuree(240);
        $sortie10->setDateLimiteInscription($finInscription->modify('-45 day'));
        $sortie10->setNbInscriptionsMax(10);
        $sortie10->setInfosSortie('Apport ton plus beau masque, tenue sanitaire exigée');
        $sortie10->setEtat($historisee);
        $sortie10->setOrganisateur($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        $sortie10->setLieu($allLieux[rand(0, sizeof($allLieux) - 1)]);
        $sortie10->setCampus($allCampus[rand(0, sizeof($allCampus) - 1)]);
        for ($i = 0; $i < 10; $i++) {
            $sortie10->addParticipant($allParticipants[rand(0, sizeof($allParticipants) - 1)]);
        }
        $manager->persist($sortie10);
        $manager->flush();
    }
}