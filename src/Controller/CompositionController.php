<?php

// src/Controller/CompositionController.php

// Controller
namespace App\Controller;

use App\Service\BrawlStarsApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompositionController extends AbstractController
{
    private BrawlStarsApiService $brawlStarsApiService;

    public function __construct(BrawlStarsApiService $brawlStarsApiService)
    {
        $this->brawlStarsApiService = $brawlStarsApiService;
    }

    #[Route('/composition', name: 'composition')]
    public function composition(Request $request): Response
    {
        try {
            $brawlers = $this->brawlStarsApiService->fetchBrawlers();
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la récupération des brawlers.');
            $brawlers = [];
        }

        return $this->render('composition/index.html.twig', [
            'brawlers' => $brawlers,
        ]);
    }
}