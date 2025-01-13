<?php
// PlayerController.php

namespace App\Controller;

use App\Service\PlayerApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
{
    private PlayerApiService $playerApiService;

    public function __construct(PlayerApiService $playerApiService)
    {
        $this->playerApiService = $playerApiService;
    }

    #[Route('/player/search', name: 'player_search', methods: ['GET', 'POST'])]
    public function search(Request $request): Response
    {
        $playerTag = null;
        $playerData = null;
        $error = null;

        // Vérification si un tag est passé dans l'URL (GET)
        $playerTag = $request->query->get('tag');
        
        if ($playerTag) {
            // Si le tag commence pas par #, nous l'ajoutons.
            if ($playerTag[0] !== '#') {
                $playerTag = '#' . $playerTag;
            }

            try {
                // Récupérer les données du joueur via le service
                $playerData = $this->playerApiService->fetchPlayerByTag($playerTag);
            } catch (\Exception $e) {
                $error = 'Impossible de trouver le joueur. Veuillez vérifier le tag ou réessayer plus tard.';
            }
        }

        return $this->render('player/search.html.twig', [
            'playerData' => $playerData,
            'error' => $error,
        ]);
    }
}