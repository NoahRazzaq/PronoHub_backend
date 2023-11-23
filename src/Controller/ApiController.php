<?php
namespace App\Controller;

use App\Entity\Team;
use App\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ApiController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/dashboard/api', name: 'app_api')]
    public function index(): Response
    {
        $leagueId = '4338';

        $currentYear = date('Y');
        $nextYear = $currentYear + 1;

        $seasonYear = $currentYear . '-' . $nextYear;

        $apiEndpoint = "https://www.thesportsdb.com/api/v1/json/3/eventsseason.php?id={$leagueId}&s={$seasonYear}";
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', $apiEndpoint);

        $data = $response->toArray();
        dd($data);

        foreach ($data['events'] as $event) {
            $team1 = $this->getOrCreateTeam($event['strHomeTeam']);
            $team2 = $this->getOrCreateTeam($event['strAwayTeam']);

            $game = new Game();
            $game->setScore1($event['intHomeScore']);
            $game->setScore2($event['intAwayScore']);
            $game->setBanner($event['strThumb']); 
            $game->setType($event['strSport']); 
            $game->setDateMatch(new \DateTime($event['dateEvent']));
            $game->setTeamId1($team1);
            $game->setTeamId2($team2);

            $this->entityManager->persist($game);

        }

        $this->entityManager->flush(); 

        return $this->render('home/index.html.twig', [
            'eventsData' => $data,
        ]);
    }

   // ...

private function getOrCreateTeam($teamName)
{
    $teamRepository = $this->entityManager->getRepository(Team::class);
    $team = $teamRepository->findOneBy(['name' => $teamName]);

    if (!$team) {
        $team = new Team();
        $team->setName($teamName);
        $this->entityManager->persist($team);
        $this->entityManager->flush(); 

    return $team;
}


}

#[Route('/dashboard/app', name: 'app_api')]
    public function getdata(): Response
    {
        $today = date('Y-m-d');

        // Replace with your league ID
        $leagueId = '4328';

        // Fetch events for this weekend
        $apiEndpoint = "https://www.thesportsdb.com/api/v1/json/3/eventsday.php?d={$today}&l={$leagueId}";
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', $apiEndpoint);

        $data = $response->toArray();
        
        dd($data);

        return $this->render('your_template.html.twig', [
            'eventsData' => $data,
        ]);
    }

}