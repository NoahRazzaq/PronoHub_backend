<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\LeagueApi;
use App\Form\LeagueApiType;
use App\Repository\GameRepository;
use App\Repository\LeagueApiRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use GMP;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/dashboard/league/api')]
class LeagueApiController extends AbstractController
{
    #[Route('/', name: 'app_league_api_index', methods: ['GET'])]
    public function index(LeagueApiRepository $leagueApiRepository): Response
    {
        return $this->render('league_api/index.html.twig', [
            'league_apis' => $leagueApiRepository->findAll(),
        ]);
    }



    #[Route('/{id}', name: 'app_league_api_show', methods: ['GET'])]
    public function show(LeagueApi $leagueApi, GameRepository $gameRepository, TeamRepository $teamRepository): Response
    {
        $rounds = $gameRepository->findRoundsByLeague($leagueApi);
        

        return $this->render('league_api/show.html.twig', [
            'league_api' => $leagueApi,
            'rounds' => $rounds,
        ]);
    }



    #[Route('/{id}/round/{roundId}', name: 'app_league_api_games_by_round', methods: ['GET'])]
    public function gamesByRound(LeagueApi $leagueApi, int $roundId, GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findBy(['leagueApi' => $leagueApi, 'round' => $roundId]);

        return $this->render('league_api/games_by_round.html.twig', [
            'league_api' => $leagueApi,
            'games' => $games,
            'roundId' => $roundId,
        ]);
    }

    #[Route('/{leagueId}/round/{roundId}/update-scores', name: 'app_league_api_update_scores', methods: ['GET'])]
public function updateScores(
    Request $request,
    int $leagueId,
    int $roundId,
    HttpClientInterface $httpClient,
    GameRepository $gameRepository,
    EntityManagerInterface $manager,
    LeagueApiRepository $leagueApiRepository
): Response {
    $seasonYear = '2023-2024';
    $league = $leagueApiRepository->find($leagueId);
    $leagueAPIid = $league->getIdentifier();
    $apiEndpoint = "https://www.thesportsdb.com/api/v1/json/3/eventsround.php?id={$leagueAPIid}&r={$roundId}&s={$seasonYear}";
    $response = $httpClient->request('GET', $apiEndpoint);
    $data = $response->toArray();

    $games = $gameRepository->findGamesByLeagueAndRound($leagueId, $roundId);

    foreach ($games as $game) {
        $matchingEvent = $this->findMatchingEvent($game, $data['events']);

        if ($matchingEvent) {
            $game->setScore1($matchingEvent['intHomeScore']);
            $game->setScore2($matchingEvent['intAwayScore']);
            $game->setIsFinished($matchingEvent['strStatus'] === 'Match Finished');

            $manager->persist($game);
        }
    }

    $manager->flush();

    return $this->redirectToRoute('app_home');
}

    public function findMatchingEvent(Game $game, array $events): ?array
{
    $team1Name = $game->getTeamId1()->getName();
    $team2Name = $game->getTeamId2()->getName();

    foreach ($events as $event) {
        if ($event['strHomeTeam'] === $team1Name && $event['strAwayTeam'] === $team2Name) {
            return $event;
        }
    }

    return null; 
}



    #[Route('/{id}', name: 'app_league_api_delete', methods: ['POST'])]
    public function delete(Request $request, LeagueApi $leagueApi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $leagueApi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($leagueApi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_league_api_index', [], Response::HTTP_SEE_OTHER);
    }
}
