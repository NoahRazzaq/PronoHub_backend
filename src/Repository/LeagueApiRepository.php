<?php

namespace App\Repository;

use App\Entity\LeagueApi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LeagueApi>
 *
 * @method LeagueApi|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeagueApi|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeagueApi[]    findAll()
 * @method LeagueApi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeagueApiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeagueApi::class);
    }

//    /**
//     * @return LeagueApi[] Returns an array of LeagueApi objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LeagueApi
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
