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
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->passwordEncoder=$encoder;
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

        $allCampus = array($campus1,$campus2,$campus3);

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

        $allVilles = array($ville1,$ville2,$ville3);

        $lieu1 = new Lieu();
        $lieu1->setNom('Tour Eiffel');
        $lieu1->setRue($faker->streetName);
        $lieu1->setLongitude($faker->longitude($min = -180, $max = 180));
        $lieu1->setLatitude($faker->latitude($min = -90, $max = 90));
        $lieu1->setVille($allVilles[rand(0,2)]);
        $manager->persist($lieu1);

        $lieu2 = new Lieu();
        $lieu2->setNom('Le Louvre');
        $lieu2->setRue($faker->streetName);
        $lieu2->setLongitude($faker->longitude($min = -180, $max = 180));
        $lieu2->setLatitude($faker->latitude($min = -90, $max = 90));
        $lieu2->setVille($allVilles[rand(0,2)]);
        $manager->persist($lieu2);



        $lieu3 = new Lieu();
        $lieu3->setNom('Le Vieux Port');
        $lieu3->setRue($faker->streetName);
        $lieu3->setLongitude($faker->longitude($min = -180, $max = 180));
        $lieu3->setLatitude($faker->latitude($min = -90, $max = 90));
        $lieu3->setVille($allVilles[rand(0,2)]);
        $manager->persist($lieu3);
        $manager->flush();

        $allLieux = array($lieu1,$lieu2,$lieu3);
        $allParticipants=array();




        for ($i = 0; $i < 50; $i++) {
            $participant = new Participant();
            $participant->setNom($faker->lastName);
            $participant->setPrenom($faker->firstName);
            $participant->setTelephone($faker->phoneNumber);
            $participant->setEmail($faker->email);
            $hash = $this->passwordEncoder->encodePassword($participant, "password");
            $participant->setPassword($hash);
            $participant->setAdministrateur(False);
            $participant->setActif(True);
            $participant->setPseudo($faker->userName);
            $participant->setRattacheA($allCampus[rand(0,2)]);
            $manager->persist($participant);
            $manager->flush();
            $allParticipants[$i]=$participant;
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
        
        $heureDebut= new DateTime();
        $finInscription= new DateTime();

        $sortie1=new Sortie();
        $sortie1->setNom('Sortie En Création');
        $sortie1->setDateHeureDebut($heureDebut->modify('+10 day'));
        $sortie1->setDuree(180);
        $sortie1->setDateLimiteInscription($finInscription->modify('+8 day'));
        $sortie1->setNbInscriptionsMax(20);
        $sortie1->setInfosSortie($faker->realText($maxNbChars = 200, $indexSize = 2));
        $sortie1->setEtat($creation);
        $sortie1->setOrganisateur($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        $sortie1->setLieu($allLieux[rand(0,sizeof($allLieux)-1)]);
        $sortie1->setCampus($allCampus[rand(0,sizeof($allCampus)-1)]);
        $manager->persist($sortie1);
        $manager->flush();

        $sortie2=new Sortie();
        $sortie2->setNom('Sortie Ouverte pas d\'inscrits');
        $sortie2->setDateHeureDebut($heureDebut->modify('+12 day'));
        $sortie2->setDuree(120);
        $sortie2->setDateLimiteInscription($finInscription->modify('+7 day'));
        $sortie2->setNbInscriptionsMax(30);
        $sortie2->setInfosSortie($faker->realText($maxNbChars = 250, $indexSize = 2));
        $sortie2->setEtat($ouverte);
        $sortie2->setOrganisateur($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        $sortie2->setLieu($allLieux[rand(0,sizeof($allLieux)-1)]);
        $sortie2->setCampus($allCampus[rand(0,sizeof($allCampus)-1)]);
        $manager->persist($sortie2);
        $manager->flush();

        $sortie3=new Sortie();
        $sortie3->setNom('Sortie ouverte avec inscrits');
        $sortie3->setDateHeureDebut($heureDebut->modify('+14 day'));
        $sortie3->setDuree(120);
        $sortie3->setDateLimiteInscription($finInscription->modify('+5 day'));
        $sortie3->setNbInscriptionsMax(25);
        $sortie3->setInfosSortie($faker->realText($maxNbChars = 150, $indexSize = 2));
        $sortie3->setEtat($ouverte);
        $sortie3->setOrganisateur($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        $sortie3->setLieu($allLieux[rand(0,sizeof($allLieux)-1)]);
        $sortie3->setCampus($allCampus[rand(0,sizeof($allCampus)-1)]);
        for ($i=0; $i<10; $i++)
        {
            $sortie3->addParticipant($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        }
        $manager->persist($sortie3);
        $manager->flush();

        $sortie4=new Sortie();
        $sortie4->setNom('Sortie clôturée car nombre max d\'inscrits atteint');
        $sortie4->setDateHeureDebut($heureDebut->modify('+5 day'));
        $sortie4->setDuree(180);
        $sortie4->setDateLimiteInscription($finInscription->modify('+4 day'));
        $sortie4->setNbInscriptionsMax(10);
        $sortie4->setInfosSortie($faker->realText($maxNbChars = 150, $indexSize = 2));
        $sortie4->setEtat($cloturee);
        $sortie4->setOrganisateur($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        $sortie4->setLieu($allLieux[rand(0,sizeof($allLieux)-1)]);
        $sortie4->setCampus($allCampus[rand(0,sizeof($allCampus)-1)]);
        for ($i=0; $i<10; $i++)
        {
            $sortie4->addParticipant($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        }
        $manager->persist($sortie4);
        $manager->flush();

        $sortie5=new Sortie();
        $sortie5->setNom('Sortie clôturée car date inscription dépassée');
        $sortie5->setDateHeureDebut($heureDebut->modify('+1 day'));
        $sortie5->setDuree(180);
        $sortie5->setDateLimiteInscription($finInscription->modify('-1 day'));
        $sortie5->setNbInscriptionsMax(15);
        $sortie5->setInfosSortie($faker->realText($maxNbChars = 200, $indexSize = 2));
        $sortie5->setEtat($cloturee);
        $sortie5->setOrganisateur($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        $sortie5->setLieu($allLieux[rand(0,sizeof($allLieux)-1)]);
        $sortie5->setCampus($allCampus[rand(0,sizeof($allCampus)-1)]);
        for ($i=0; $i<10; $i++)
        {
            $sortie5->addParticipant($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        }
        $manager->persist($sortie5);
        $manager->flush();

        $sortie6=new Sortie();
        $sortie6->setNom('Sortie en cours');
        $sortie6->setDateHeureDebut($heureDebut->modify('-1 hour'));
        $sortie6->setDuree(240);
        $sortie6->setDateLimiteInscription($finInscription->modify('-1 day'));
        $sortie6->setNbInscriptionsMax(17);
        $sortie6->setInfosSortie($faker->realText($maxNbChars = 100, $indexSize = 2));
        $sortie6->setEtat($enCours);
        $sortie6->setOrganisateur($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        $sortie6->setLieu($allLieux[rand(0,sizeof($allLieux)-1)]);
        $sortie6->setCampus($allCampus[rand(0,sizeof($allCampus)-1)]);
        for ($i=0; $i<17; $i++)
        {
            $sortie6->addParticipant($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        }
        $manager->persist($sortie6);
        $manager->flush();

        $sortie7=new Sortie();
        $sortie7->setNom('Sortie terminée depuis moins d\'un mois');
        $sortie7->setDateHeureDebut($heureDebut->modify('-15 day'));
        $sortie7->setDuree(240);
        $sortie7->setDateLimiteInscription($finInscription->modify('-18 day'));
        $sortie7->setNbInscriptionsMax(23);
        $sortie7->setInfosSortie($faker->realText($maxNbChars = 150, $indexSize = 2));
        $sortie7->setEtat($terminee);
        $sortie7->setOrganisateur($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        $sortie7->setLieu($allLieux[rand(0,sizeof($allLieux)-1)]);
        $sortie7->setCampus($allCampus[rand(0,sizeof($allCampus)-1)]);
        for ($i=0; $i<23; $i++)
        {
            $sortie7->addParticipant($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        }
        $manager->persist($sortie7);
        $manager->flush();

        $sortie8=new Sortie();
        $sortie8->setNom('Sortie terminée depuis plus d\'un mois');
        $sortie8->setDateHeureDebut($heureDebut->modify('-45 day'));
        $sortie8->setDuree(240);
        $sortie8->setDateLimiteInscription($finInscription->modify('-48 day'));
        $sortie8->setNbInscriptionsMax(12);
        $sortie8->setInfosSortie($faker->realText($maxNbChars = 180, $indexSize = 2));
        $sortie8->setEtat($historisee);
        $sortie8->setOrganisateur($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        $sortie8->setLieu($allLieux[rand(0,sizeof($allLieux)-1)]);
        $sortie8->setCampus($allCampus[rand(0,sizeof($allCampus)-1)]);
        for ($i=0; $i<12; $i++)
        {
            $sortie8->addParticipant($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        }
        $manager->persist($sortie8);
        $manager->flush();

        $sortie9=new Sortie();
        $sortie9->setNom('Sortie annulée mais pas encore archivée');
        $sortie9->setDateHeureDebut($heureDebut->modify('-20 day'));
        $sortie9->setDuree(240);
        $sortie9->setDateLimiteInscription($finInscription->modify('-23 day'));
        $sortie9->setNbInscriptionsMax(10);
        $sortie9->setInfosSortie($faker->realText($maxNbChars = 150, $indexSize = 2));
        $sortie9->setEtat($annulee);
        $sortie9->setOrganisateur($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        $sortie9->setLieu($allLieux[rand(0,sizeof($allLieux)-1)]);
        $sortie9->setCampus($allCampus[rand(0,sizeof($allCampus)-1)]);
        for ($i=0; $i<10; $i++)
        {
            $sortie9->addParticipant($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        }
        $manager->persist($sortie9);
        $manager->flush();

        $sortie10=new Sortie();
        $sortie10->setNom('Sortie annulée et archivée');
        $sortie10->setDateHeureDebut($heureDebut->modify('-40 day'));
        $sortie10->setDuree(240);
        $sortie10->setDateLimiteInscription($finInscription->modify('-45 day'));
        $sortie10->setNbInscriptionsMax(10);
        $sortie10->setInfosSortie($faker->realText($maxNbChars = 150, $indexSize = 2));
        $sortie10->setEtat($annulee);
        $sortie10->setOrganisateur($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        $sortie10->setLieu($allLieux[rand(0,sizeof($allLieux)-1)]);
        $sortie10->setCampus($allCampus[rand(0,sizeof($allCampus)-1)]);
        for ($i=0; $i<10; $i++)
        {
            $sortie10->addParticipant($allParticipants[rand(0,sizeof($allParticipants)-1)]);
        }
        $manager->persist($sortie10);
        $manager->flush();
    }
}