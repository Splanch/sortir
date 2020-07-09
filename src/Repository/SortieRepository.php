<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }


 /*   public function findSortie(Participant $userConnecte)
    {


        $qb = $this->createQueryBuilder('s');
        $qb->andWhere($userConnecte ==='s.organisateur');
        $qb->join('s.etat', 'e');
        $qb->addSelect('e');
        $query=$qb->getQuery();
        $sorties = $query->getResult();

        return $sorties;
    }
 */



    public function findSortieParametre($user,$searchParameters): ?array
    {
        $result=$this->createQueryBuilder('s');

        if ($searchParameters['organiseesParMoi']) {
            $result = $result->andWhere('s.organisateur = :org')
                ->setParameter('org', $user);
       }
//        if($searchParameters['jeSuisInscrit']){
//
//        }
        if(!empty($searchParameters['keywords'])){
            $kw = $searchParameters['keywords'];
            $result = $result->andWhere('s.nom LIKE :kw')
                ->setParameter('kw', "%$kw%");
        }
        //case Sortie Passes n'est pas coché
        if(!$searchParameters['sortiesPassees']){
            $result = $result->andWhere('s.dateHeureDebut => :dateDebut')
                ->setParameter('dateDebut', $searchParameters['dateDebut']);
        }
        if($searchParameters['sortiesPassees']&& !$searchParameters['organiseesParMoi']
            &&!$searchParameters['jeSuisInscrit'] &&!$searchParameters['nonInscrit']){
            $result = $result->andWhere('s.dateHeureDebut < now')
                ->setParameter('now', new \DateTime());
        }else{
            $result = $result->andWhere('s.dateHeureDebut < :dateFin')
                ->setParameter(':dateFin', $searchParameters['dateFin']);
        }

            $result = $result

                ->join('s.etat', 'e')
                ->addSelect('e')
                ->andWhere("e.libelle !='Historisée'")
                ->join('s.participants', 'p')
                ->addSelect('p')
                ->groupBy('s.id')
                ->getQuery()
                ->getArrayResult();

         return $result;
    }

}
