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
            $result=$result->andWhere('s.organisateur = :val')
                ->setParameter('val', $user)
                ->join('s.etat', 'e')
                ->addSelect('e')
                ->join('s.participants', 'p')
                ->addSelect('p')
                ->getQuery()
                ->getArrayResult();
        }
         return $result;
    }

}
