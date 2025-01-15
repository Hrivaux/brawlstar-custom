<?php

namespace App\Service;

class BrawlStarsApiService extends AbstractApiService
{
    public function fetchBrawlers(): array
    {
        $apiUrl = 'https://api.brawlstars.com/v1/brawlers';
        return $this->request($apiUrl);
    }
}