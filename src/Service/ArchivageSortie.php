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


}