<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Game;
use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


class ApiController extends AbstractController
{
    private $entityManager;
    private $translator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
                $this->translator = $translator;

    }

    #[Route('/dashboard/createTeam', name: 'app_api_team')]
    public function createTeams()
    {
        $leagueId = '4328';

        $apiEndpoint = "https://www.thesportsdb.com/api/v1/json/3/search_all_teams.php?l=English%20Premier%20League";
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', $apiEndpoint);

        $data = $response->toArray();

        foreach ($data['teams'] as $teamData) {
            $categoryName = $this->translateSport($teamData['strSport']);

            $category = $this->getOrCreateCategory($categoryName);

            $this->createTeam($teamData['strTeam'], $teamData['strTeamBadge'], $category);
        }

        return new Response('Teams created successfully!');
    }
    private function createTeam($teamName, $logo)
    {
        $teamRepository = $this->entityManager->getRepository(Team::class);
        $team = $teamRepository->findOneBy(['name' => $teamName]);

        if (!$team) {
            $team = new Team();
            $team->setName($teamName);
            $team->setLogo($logo); 
            $this->entityManager->persist($team);
            $this->entityManager->flush();
        }
    }

    
public function translateSport($sport)
{
    return $this->translator->trans("$sport", [], 'messages');
}

    #[Route('/dashboard/app', name: 'app_api_app')]
    public function fetchAndStoreEvents()
    {
        $leagueId = '4328';

        $currentYear = date('Y');
        $nextYear = $currentYear + 1;

        $seasonYear = $currentYear . '-' . $nextYear;

        $round = 10;

        $apiEndpoint = "https://www.thesportsdb.com/api/v1/json/3/eventsround.php?id={$leagueId}&r={$round}&s={$seasonYear}";
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', $apiEndpoint);

        $data = $response->toArray();

        foreach ($data['events'] as $event) {
            $categoryName = $event['strSport'];
            $category = $this->getOrCreateCategory($categoryName);

            $team1 = $this->getOrCreateTeam($event['strHomeTeam'], $category);
            $team2 = $this->getOrCreateTeam($event['strAwayTeam'], $category);

            $dateTimeString = $event['dateEvent'] . ' ' . $event['strTime'];
            $dateMatch = new \DateTime($dateTimeString);

            $game = new Game();
            $game->setScore1($event['intHomeScore']);
            $game->setScore2($event['intAwayScore']);
            $game->setBanner($event['strThumb']);
            $game->setDateMatch($dateMatch);
            $game->setTeamId1($team1);
            $game->setTeamId2($team2);
            $game->setCategory($category);
            $game->setIsFinished($event['strStatus'] === 'Match Finished');

            $this->entityManager->persist($game);
        }

        $this->entityManager->flush();
        return $data;
    }

  

    private function getOrCreateCategory($categoryName)
    {
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);

        if (!$category) {
            $category = new Category();
            $category->setName($categoryName);
            $this->entityManager->persist($category);
            $this->entityManager->flush();
        }

        return $category;
    }

    private function getOrCreateTeam($teamName, Category $category)
    {
        $teamRepository = $this->entityManager->getRepository(Team::class);
        $team = $teamRepository->findOneBy(['name' => $teamName]);

        if (!$team) {
            $team = new Team();
            $team->setName($teamName);
            $team->setCategory($category);
            $this->entityManager->persist($team);
            $this->entityManager->flush();
        }

        return $team;
    }

  
}
