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

        foreach ($sorties as $sort){

            if (($sort->getDateHeureDebut()) < $date){

                $sort->setEtat($etat);
                $this->em->persist($sort);


            }

        }
        $this->em->flush();

    }

    public function clotureEtEnCoursSorties(){
        $repo = $this->em->getRepository(Sortie::class);
        $repoEtats = $this->em->getRepository(Etat::class);

        $sorties = $repo->findAll();
        $cloture= $repoEtats->findOneByLibelle('Clôturée');
        $enCours= $repoEtats->findOneByLibelle('En cours');
        $archiver = $repoEtats->findOneByLibelle('Historisée');
        $bob=0;
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('Europe/Paris'));

        foreach ($sorties as $sortie){
                $datedefin = $sortie->getDateHeureDebut();
                $datedefin->modify('+'.$sortie->getDuree().'minutes');
                return $datedefin;

            if (($date>=($sortie->getDateHeureDebut()))&&($date<=$datedefin)  ){

                $sortie->setEtat($enCours);
                $this->em->persist($sortie);
                $bob++;
            }

            if (($date>$datedefin) && ($sortie->getEtat()!=$archiver)  ){

                $sortie->setEtat($cloture);
                $this->em->persist($sortie);

            }
        }
        $this->em->flush();
        return $bob;
    }
}