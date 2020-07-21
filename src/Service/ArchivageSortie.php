<?php
namespace App\Service;

use App\Entity\Etat;
use App\Entity\Sortie;

use Doctrine\ORM\EntityManagerInterface;

class ArchivageSortie
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

    }

    public function archiverSorties()
    {
       
        $repo = $this->em->getRepository(Sortie::class);
        $repoEtats = $this->em->getRepository(Etat::class);

        $sorties = $repo->findAll();
        $etat = $repoEtats->findOneByLibelle('Historisée');

        $date = new \DateTime();
        $date->modify('-30 day');

        foreach ($sorties as $sortie){

            if (($sortie->getDateHeureDebut()) < $date){

                $sortie->setEtat($etat);
                $this->em->persist($sortie);
            }

        }
        $this->em->flush();

    }

    public function clotureEtEnCoursSorties(){
        $repo = $this->em->getRepository(Sortie::class);
        $repoEtats = $this->em->getRepository(Etat::class);

        $sorties = $repo->findAll();
        $cloturer= $repoEtats->findOneByLibelle('Clôturée');
        $enCours= $repoEtats->findOneByLibelle('En cours');
        $archiver = $repoEtats->findOneByLibelle('Historisée');
        $terminer = $repoEtats->findOneByLibelle('Terminée');

        $bob=0;
        $bob2=0;
        $bob3=0;
        $bob4=0;
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('Europe/Paris'));

        foreach ($sorties as $sortie){
                $datedefin = $sortie->getDateHeureDebut();
                $datedefin->setTimezone(new \DateTimeZone('Europe/Paris'));
                $datedefin->modify('+'.$sortie->getDuree().'minutes');
                $bob++;

           /* if (($date > $sortie->getDateHeureDebut())){
                $sortie->setEtat($enCours);
                $this->em->persist($sortie);
                $bob2++;
            }*/

            if (($date>$sortie->getDateLimiteInscription()) && ($sortie->getEtat()!=$archiver) &&($sortie->getEtat()!=$cloturer) &&($sortie->getEtat()!=$terminer)&&($sortie->getEtat()!=$enCours)  ){
                $sortie->setEtat($cloturer);
                $this->em->persist($sortie);
                $bob3++;

            }

            if($date>$datedefin && ($sortie->getEtat()!=$archiver)&& ($sortie->getEtat()!=$terminer)){
                $sortie->setEtat($terminer);
                $this->em->persist($sortie);
                $bob4++;
            }
        }
        $this->em->flush();
        return [$bob,$bob2,$bob3,$bob4];
    }
}