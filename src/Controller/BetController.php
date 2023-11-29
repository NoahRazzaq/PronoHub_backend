<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Form\BetType;
use App\Repository\BetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/bet')]
class BetController extends AbstractController
{
    #[Route('/', name: 'app_bet_index', methods: ['GET'])]
    public function index(BetRepository $betRepository): Response
    {
        return $this->render('bet/index.html.twig', [
            'bets' => $betRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_bet_show', methods: ['GET'])]
    public function show(Bet $bet): Response
    {
        dd($bet->getUsers()->getValues());
        return $this->render('bet/show.html.twig', [
            'bet' => $bet,
        ]);
    }


    #[Route('/{id}', name: 'app_bet_delete', methods: ['POST'])]
    public function delete(Request $request, Bet $bet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($bet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bet_index', [], Response::HTTP_SEE_OTHER);
    }
}
