<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(GameRepository $gameRepository): Response
    {
        $upcomingMatches = $gameRepository->findUpcomingMatches();

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'upcomingMatches' => $upcomingMatches,
        ]);
    }


}
