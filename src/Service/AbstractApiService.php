<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractApiService
{
    protected HttpClientInterface $client;
    protected string $apiToken;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->apiToken = $_ENV['BRAWLSTARS_API_TOKEN'] ?? '';

        if (empty($this->apiToken)) {
            throw new \RuntimeException('Le token API est manquant. Veuillez le configurer dans .env.');
        }
    }

    protected function request(string $url): array
    {
        try {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiToken,
                ],
            ]);

            return $response->toArray();
        } catch (\Exception $e) {
            throw new \RuntimeException('Erreur lors de la requête vers l\'API : ' . $e->getMessage());
        }
    }
}