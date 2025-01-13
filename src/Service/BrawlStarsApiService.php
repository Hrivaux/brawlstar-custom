<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class BrawlStarsApiService
{
    private HttpClientInterface $client;
    private string $apiToken;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->apiToken = $_ENV['BRAWLSTARS_API_TOKEN'] ?? '';
    }

    
    public function fetchBrawlers(): array
    {
        $apiUrl = 'https://api.brawlstars.com/v1/brawlers';

        try {
            $response = $this->client->request('GET', $apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiToken,
                ],
            ]);

            return $response->toArray(); // Convertit la réponse JSON en tableau PHP
        } catch (\Exception $e) {
            // Gestion des erreurs
            throw new \Exception('Erreur lors de la récupération des brawlers : ' . $e->getMessage());
        }
    }
}