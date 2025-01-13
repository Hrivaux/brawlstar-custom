<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PlayerRankingsService
{
    private HttpClientInterface $client;
    private string $apiToken;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->apiToken = $_ENV['BRAWLSTARS_API_TOKEN'] ?? '';
    }

    /**
     * Récupère les classements des joueurs pour un pays donné.
     *
     * @param string $countryCode Le code du pays (ex: 'FR', 'US', 'global').
     * @return array Les données des classements des joueurs.
     * @throws \Exception Si l'API retourne une erreur.
     */
    public function fetchRankingsByCountry(string $countryCode): array
{
    $apiUrl = "https://api.brawlstars.com/v1/rankings/$countryCode/players";

    try {
        $response = $this->client->request('GET', $apiUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiToken,
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        $data = $response->toArray();

        // Debug : Assurez-vous que les trophées sont bien présents
        // dd($data); // Décommentez pour faire le debug et vérifier la structure

        // Retourner les éléments contenant les informations des joueurs
        return $data['items'] ?? []; // Assurez-vous que 'items' existe
    } catch (\Exception $e) {
        throw new \Exception('Impossible de récupérer les classements : ' . $e->getMessage());
    }
}

}