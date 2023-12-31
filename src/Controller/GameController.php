<?php

namespace App\Controller;

use App\Entity\Game;
use App\Event\CreateMatchesEvent;
use App\Form\CreateMatchesType;
use App\Form\Game1Type;
use App\Form\TeamGameFormType;
use App\Repository\BetRepository;
use App\Repository\GameRepository;
use App\Repository\LeagueApiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;


#[Route('/dashboard/game')]
class GameController extends AbstractController
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    #[Route('/', name: 'app_game_index', methods: ['GET'])]
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('game/index.html.twig', [
            'games' => $gameRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_game_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LeagueApiRepository $leagueApiRepository)
    {
        try {
     
            $form = $this->createForm(TeamGameFormType::class);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                
                $event = new CreateMatchesEvent($data['leagueId'], $data['round'], $data['seasonYear']);
                $this->dispatcher->dispatch($event);
    
                $this->addFlash('success', 'Matches created successfully!');
    
                return $this->redirectToRoute('app_game_index');
            }
        } catch (HttpException $exception) {
            return new Response($exception->getMessage(), $exception->getStatusCode());
        }

        return $this->render('game/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_game_show', methods: ['GET'])]
    public function show(Game $game): Response
    {
 
    $bets = $game->getBets()->getValues();
        
    


        return $this->render('game/show.html.twig', [
            'game' => $game,
            'bets' => $bets,
        ]);
    }

    #[Route('/{id}/update-bet-status/{betId}/{status}', name: 'app_game_update_bet_status', methods: ['GET'])]
public function updateBetStatus(Request $request, Game $game, int $betId, string $status, EntityManagerInterface $manager, BetRepository $betRepository): Response
{
    $validStatuses = ['valid', 'pending', 'finished'];

    if (!in_array($status, $validStatuses)) {
        throw $this->createNotFoundException('invalide.');
    }

    $bet = $betRepository->find($betId);

    if (!$bet || $bet->getGame() !== $game) {
        throw $this->createNotFoundException('paris non trouver');
    }

    $bet->setStatus($status);
    $manager->flush();

    return $this->redirectToRoute('app_game_show', ['id' => $game->getId()]);
}




    #[Route('/{id}', name: 'app_game_delete', methods: ['POST'])]
    public function delete(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->request->get('_token'))) {
            $entityManager->remove($game);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
    }
}
