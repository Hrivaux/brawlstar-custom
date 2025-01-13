<?php

namespace App\Controller;

use App\Service\PlayerRankingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RankingsController extends AbstractController
{
    private PlayerRankingsService $playerRankingsService;

    public function __construct(PlayerRankingsService $playerRankingsService)
    {
        $this->playerRankingsService = $playerRankingsService;
    }

    #[Route('/rankings', name: 'rankings')]
    public function rankings(Request $request): Response
{
    $countryCode = $request->query->get('country', 'global'); // Par défaut 'global'
    $countries = [
        'global', 'FR', 'US', 'DE', 'ES', 'IT', 'JP', 'BR', 'IN', 'KR', 'CN'
        // Ajoutez d'autres codes si nécessaire
    ];

    $rankings = [];
    $error = null;

    try {
        $rankings = $this->playerRankingsService->fetchRankingsByCountry($countryCode);
    } catch (\Exception $e) {
        $error = $e->getMessage();
    }

    // Déboguer la réponse
    dump($rankings); // Ajoutez un dump pour déboguer et vérifier la structure des données

    return $this->render('rankings/index.html.twig', [
        'countries' => $countries,
        'selectedCountry' => $countryCode,
        'rankings' => $rankings, // Pas besoin de ['items'] ici si vous l'avez déjà
        'error' => $error,
    ]);
}


}