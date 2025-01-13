<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class PlayerApiService
{
    private HttpClientInterface $client;
    private string $apiToken;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;

        // Récupération du token API depuis les variables d'environnement
        $this->apiToken = $_ENV['BRAWLSTARS_API_TOKEN'] ?? '';

        if (empty($this->apiToken)) {
            throw new \RuntimeException('Le token API Brawl Stars est manquant. Veuillez le définir dans votre fichier .env.');
        }
    }

    public function fetchPlayerByTag(string $playerTag): array
    {
        // Construction de l'URL API avec encodage du tag
        $apiUrl = sprintf('https://api.brawlstars.com/v1/players/%s', urlencode($playerTag));

        try {
            // Requête vers l'API Brawl Stars
            $response = $this->client->request('GET', $apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiToken,
                ],
            ]);

            // Transformation de la réponse JSON en tableau PHP
            return $response->toArray();
        } catch (ClientExceptionInterface | ServerExceptionInterface $e) {
            // Gestion des erreurs 4xx ou 5xx
            throw new \RuntimeException('Erreur lors de la requête vers l\'API Brawl Stars : ' . $e->getMessage());
        } catch (TransportExceptionInterface $e) {
            // Gestion des erreurs réseau
            throw new \RuntimeException('Erreur réseau lors de la requête vers l\'API Brawl Stars : ' . $e->getMessage());
        }
    }
}