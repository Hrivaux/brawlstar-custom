<?php

namespace App\Controller;

use App\Service\BrawlStarsApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrawlController extends AbstractController
{
    private BrawlStarsApiService $brawlStarsApiService;

    public function __construct(BrawlStarsApiService $brawlStarsApiService)
    {
        $this->brawlStarsApiService = $brawlStarsApiService;
    }

    #[Route('/brawlers', name: 'brawlers_list')]
    public function list(): Response
    {
        $brawlers = $this->brawlStarsApiService->fetchBrawlers();

        return $this->render('brawlers/list.html.twig', [
            'brawlers' => $brawlers['items'],
        ]);
    }
}