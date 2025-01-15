<?php

namespace App\Service;

class PlayerRankingsService extends AbstractApiService
{
    public function fetchRankingsByCountry(string $countryCode): array
    {
        $apiUrl = sprintf('https://api.brawlstars.com/v1/rankings/%s/players', $countryCode);
        $data = $this->request($apiUrl);

        return $data['items'] ?? []; // Vérification que 'items' existe dans la réponse
    }
}