<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;


class PeticionesUrlsService
{
    private string $scraperUrl = 'http://scraper-service:3000/scrape';
    private Client $client;

    public function __construct()
    {
        // Crear Guzzle manualmente
        $this->client = new Client([
            'timeout' => 30,
            'connect_timeout' => 10,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Scraper)'
            ]
        ]);
    }


    public function peticionesUrlBatch(array $batch): array
    {


        $promises = [];
        foreach ($batch as $url) {
            $promises[$url] = $this->client->postAsync($this->scraperUrl, [
                'json' => ['url' => $url]
            ]);

          

        }
        $responses = Utils::settle($promises)->wait();

        return $responses;
    }


    public function peticionesUrl(string $url): array
    {


        $promises = [];

        $promises[] = $this->client->postAsync($this->scraperUrl, [
            'json' => ['url' => $url]
        ]);

        $responses = Utils::settle($promises)->wait();

        return $responses;
    }
}
