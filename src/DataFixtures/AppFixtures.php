<?php

namespace App\DataFixtures;

use App\Entity\Bet;
use App\Entity\Game;
use App\Entity\LeaderBoard;
use App\Entity\League;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 12; $i++) {
            $teams1 = new Team();
            $teams1->setName($faker->name)
                ->setLogo($faker->text)
                ->setType($faker->word);

            $manager->persist($teams1);

            $teams2 = new Team();
            $teams2->setName($faker->name)
                ->setLogo($faker->text)
                ->setType($faker->word);

            $manager->persist($teams2);

            $games = new Game();
            $games->setScore1($faker->randomDigit)
                ->setScore2($faker->randomDigit)
                ->setBanner($faker->text)
                ->setDateMatch($faker->dateTime())
                ->setTeamId1($teams1)
                ->setTeamId2($teams2)
                ->setType($faker->word);

            $manager->persist($games);
        }

        for ($i = 0; $i < 9; $i++) {
            
        
            $leagues = new League();
            $leagues->setName($faker->name)
                   ->setCodeInvite($faker->randomNumber(5, true));
            
            $manager->persist($leagues);
        
            // Ajouter la ligue à l'utilisateur après la création de la ligue

        
                $leaderboards = new LeaderBoard();
                $leaderboards->setPosition($faker->randomDigit)
                             ->setPoints($faker->randomDigit)
                             ->setNbWin($faker->randomDigit)
                             ->setNbLose($faker->randomDigit);
    
                $manager->persist($leaderboards);
            }
    
            for ($i = 0; $i < 12; $i++) {
                $bets = new Bet();
                $bets
                    ->setGame($games)
                     ->setLeague($leagues)
                     ->setTeam($teams1)
                     ->setIsDraw($faker->boolean)
                     ->setStatus($faker->name);
    
                $manager->persist($bets);
            }


        $manager->flush();
    }
}
