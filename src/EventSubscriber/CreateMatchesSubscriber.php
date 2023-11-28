<?php

namespace App\EventSubscriber;

use App\Entity\Category;
use App\Entity\Game;
use App\Entity\LeagueApi;
use App\Entity\Team;
use App\Event\CreateMatchesEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

use function PHPUnit\Framework\throwException;

class CreateMatchesSubscriber implements EventSubscriberInterface
{
    private $entityManager;
    private $translator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CreateMatchesEvent::class => 'onCreateMatches',
        ];
    }

    public function onCreateMatches(CreateMatchesEvent $event)
    {
        $leagueId = $event->getLeagueId();
        $round = $event->getRound();
        $seasonYear = $event->getSeasonYear();

        // $currentYear = date('Y');
        // $nextYear = $currentYear + 1;

        // $seasonYear = $currentYear . '-' . $nextYear;

        $apiEndpoint = "https://www.thesportsdb.com/api/v1/json/3/eventsround.php?id={$leagueId}&r={$round}&s={$seasonYear}";
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', $apiEndpoint);
        $data = $response->toArray();



        foreach ($data['events'] as $event) {
            $leagueName = $this->formatLeagueName($event['strLeague']);
        }

        $teamApiEndpoint = "https://www.thesportsdb.com/api/v1/json/3/search_all_teams.php?l={$leagueName}";
        $teamResponse = $httpClient->request('GET', $teamApiEndpoint);
        $teamData = $teamResponse->toArray();

        foreach ($teamData['teams'] as $teamData) {
            $team = $this->createTeam($teamData['strTeam'], $teamData['strTeamBadge']);
        }

        foreach ($data['events'] as $event) {
            $categoryName = $this->translateSport($event['strSport']);
            $category = $this->getOrCreateCategory($categoryName);

            $team1 = $this->getOrCreateTeam($event['strHomeTeam'], $category);
            $team2 = $this->getOrCreateTeam($event['strAwayTeam'], $category);

            $dateTimeString = $event['dateEvent'] . ' ' . $event['strTime'];
            $dateMatch = new \DateTime($dateTimeString);

            $leagueApi = $this->entityManager->getRepository(LeagueApi::class)->findOneBy(['identifier' => $leagueId]);

        $existingGame = $this->entityManager->getRepository(Game::class)->findOneBy([
            'round' => $round,
            'leagueApi' => $leagueApi,
        ]);

        if (!$existingGame) {
            $game = new Game();
            $game->setScore1($event['intHomeScore']);
            $game->setScore2($event['intAwayScore']);
            $game->setBanner($event['strThumb']);
            $game->setDateMatch($dateMatch);
            $game->setTeamId1($team1);
            $game->setTeamId2($team2);
            $game->setCategory($category);
            $game->setIsFinished($event['strStatus'] === 'Match Finished');
            $game->setRound($round);
            $game->setLeagueApi($leagueApi);

            $this->entityManager->persist($game);
        } else {
            throw new HttpException(Response::HTTP_CONFLICT, 'Games already exist');
        }

            $this->entityManager->persist($game);
        }

        $this->entityManager->flush();

        return new Response('Games created successfully!');
    }
    private function formatLeagueName($leagueName)
    {
        return str_replace(' ', '_', $leagueName);
    }


    private function translateSport($sport)
    {
        return $this->translator->trans("$sport", [], 'messages');
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

        return $team;
    }
}
