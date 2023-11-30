<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\LeagueApi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function findRoundsByLeague(LeagueApi $leagueApi): array
{
    $rounds = $this->createQueryBuilder('g')
        ->select('DISTINCT g.round')
        ->where('g.leagueApi = :league')
        ->setParameter('league', $leagueApi)
        ->orderBy('g.round', 'ASC') 
        ->getQuery()
        ->getArrayResult();

    $roundValues = array_column($rounds, 'round');

    return $roundValues;
}

    public function findGamesByLeagueAndRound(int $leagueId, int $roundId)
    {
        return $this->createQueryBuilder('g')
            ->join('g.leagueApi', 'l')
            ->where('l.id = :leagueId')
            ->andWhere('g.round = :roundId')
            ->setParameter('leagueId', $leagueId)
            ->setParameter('roundId', $roundId)
            ->getQuery()
            ->getResult();
    }

    public function findUpcomingMatches(): array
{
    return $this->createQueryBuilder('g')
        ->where('g.dateMatch > :now')
        ->setParameter('now', new \DateTime())
        ->orderBy('g.dateMatch', 'ASC') // Assurez-vous que les matchs sont triés par ordre croissant de date
        ->setMaxResults(3) // Limitez à 3 résultats
        ->getQuery()
        ->getResult();
}

//    /**
//     * @return Game[] Returns an array of Game objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Game
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
