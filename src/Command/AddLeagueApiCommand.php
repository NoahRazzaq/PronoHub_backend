<?php

namespace App\Command;

use App\Entity\LeagueApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:add-league-api',
    description: 'Add League API entries',
)]
class AddLeagueApiCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add League API entries');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $leagues = [
            ['name' => 'Ligue 1', 'id' => 4334],
            ['name' => 'Premier League', 'id' => 4328],
            ['name' => 'Top 14', 'id' => 4430],
            ['name' => 'NFL', 'id' => 4391],
        ];

       
        foreach ($leagues as $league) {
            try {
                $existLeague = $this->entityManager->getRepository(LeagueApi::class)->findOneBy(['identifier' => $league['id']]);
        
                if (!$existLeague) {
                    $leagueApi = new LeagueApi();
                    $leagueApi->setName($league['name']);
                    $leagueApi->setIdentifier((string)$league['id']);
        
                    $this->entityManager->persist($leagueApi);
                } else {
                    $output->writeln("League '{$league['name']}' already exists. Skipping.");
                }
            } catch (\Exception $e) {
                $output->writeln("An error occurred: " . $e->getMessage());
            }
        }

        $this->entityManager->flush();

        $output->writeln('League API added successfully.');

        return Command::SUCCESS;
    }
}

