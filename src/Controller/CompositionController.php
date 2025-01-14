<?php

namespace App\Controller;

use App\Service\BrawlStarsApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(): Response
    {
        try {
            $brawlers = $this->brawlStarsApiService->fetchBrawlers();
        } catch (\Exception $e) {
            $brawlers = [];
            $this->addFlash('error', $e->getMessage());
        }

        return $this->render('composition/index.html.twig', [
            'brawlers' => $brawlers,
        ]);
    }
}