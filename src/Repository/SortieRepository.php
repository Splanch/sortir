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


    public function findAllSorties()
    {

        $result = $this->createQueryBuilder('s')
            ->join('s.campus', 'c')
            ->addSelect('c')
            ->join('s.etat', 'e')
            ->addSelect('e')
            ->join('s.organisateur', 'o')
            ->addSelect('o')
            ->andWhere("e.libelle !='Historisée'")
            ->leftJoin('s.participants', 'p')
            ->addSelect('p')
            ->getQuery()
            ->getResult();

        return $result;

    }


    public function findSortieParametre($user, $searchParameters): ?array
    {
        $aujourdhui = new \DateTime();

        $result = $this->createQueryBuilder('s')
            ->andWhere('s.dateHeureDebut <= :dateFin')
            ->setParameter(':dateFin', $searchParameters['dateFin']);
//        Sorties organisées par moi
        if ($searchParameters['organiseesParMoi']) {
            $result = $result
                ->andWhere('s.organisateur = :org')
                ->setParameter('org', $user);
        }
//        Sorties auxquelles je suis inscrit/e
        if ($searchParameters['jeSuisInscrit']) {
            $result = $result
                ->andWhere(':participant MEMBER OF s.participants')
                ->setParameter('participant', $user);
        }
//        Sorties auxquelles je ne suis pas inscrit/e
        if ($searchParameters['nonInscrit']) {
            $result = $result
                ->andWhere(':participant  NOT MEMBER OF s.participants')
                ->setParameter('participant', $user);
        }
//        Mot-clé
        if (!empty($searchParameters['keywords'])) {
            $kw = $searchParameters['keywords'];
            $result = $result
                ->andWhere('s.nom LIKE :kw')
                ->setParameter('kw', "%$kw%");
        }
//        Sortie passées
        if ($searchParameters['sortiesPassees']) {
            $result = $result
                ->andWhere('s.dateHeureDebut < :aujourdhui')
                ->setParameter('aujourdhui', $aujourdhui);

        }else{
            $result = $result
                ->andWhere('s.dateHeureDebut >= :dateDebut')
                ->setParameter('dateDebut', $searchParameters['dateDebut'])
                ->andWhere('s.dateHeureDebut <= :dateFin')
                ->setParameter('dateFin', $searchParameters['dateFin']);
        }

        $result = $result
            ->andWhere('s.campus = :campus')
            ->setParameter('campus', $searchParameters['campus'])
            ->join('s.etat', 'e')
            ->addSelect('e')
            ->andWhere("e.libelle !='Historisée'")
            ->leftJoin('s.participants', 'p')
            ->addSelect('p')
            ->join('s.organisateur', 'o')
            ->addSelect('o')
            ->getQuery()
            ->getResult();

        return $result;
    }

}
