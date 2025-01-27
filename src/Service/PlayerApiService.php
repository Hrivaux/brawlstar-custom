<?php

namespace App\Service;

class PlayerApiService extends AbstractApiService
{
    public function fetchPlayerByTag(string $playerTag): array
    {
        $apiUrl = sprintf('https://api.brawlstars.com/v1/players/%s', urlencode($playerTag));
        return $this->request($apiUrl);
    }

    public function fetchPlayerBattleLog(string $playerTag): array
    {
        $apiUrl = sprintf('https://api.brawlstars.com/v1/players/%s/battlelog', urlencode($playerTag));
        return $this->request($apiUrl);
    }
}