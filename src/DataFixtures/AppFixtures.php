<?php

namespace App\DataFixtures;

use App\Entity\Bet;
use App\Entity\Game;
use App\Entity\LeaderBoard;
use App\Entity\League;
use App\Entity\Team;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
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
            $users = new User();
            $hashedPassword = $this->passwordHasher->hashPassword($users, 'password');
            $users->setLastName($faker->name)
                   ->setName($faker->name)
                   ->setEmail($faker->email)
                   ->setPassword($hashedPassword)
                   ->setRoles(['ROLE_USER']);
            
            $manager->persist($users);
        
            $leagues = new League();
            $leagues->setName($faker->name)
                   ->setCodeInvite($faker->randomNumber(5, true))
                   ->setIdUserCreator($users);
            
            $manager->persist($leagues);
        
            // Ajouter la ligue à l'utilisateur après la création de la ligue
            $users->addLeague($leagues);

            $manager->persist($users);
        
                $leaderboards = new LeaderBoard();
                $leaderboards->setPosition($faker->randomDigit)
                             ->addUser($users)
                             ->setPoints($faker->randomDigit)
                             ->setNbWin($faker->randomDigit)
                             ->setNbLose($faker->randomDigit);
    
                $manager->persist($leaderboards);
            }
    
            for ($i = 0; $i < 12; $i++) {
                $bets = new Bet();
                $bets->addUser($users)
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
