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
            'timeout' => 120,
            'connect_timeout' => 10,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Scraper)'
            ]
        ]);
    }


    public function peticionesUrlSelectores(string $url, string $selector): array
    {
        $promises = [];

        $promises[] = $this->client->postAsync($this->scraperUrl, [
            'json' => [
                'url' => $url,
                'selector' => $selector,
            ]
        ]);

        $responses = Utils::settle($promises)->wait();


       
        if (
            !isset($responses[0]['state']) ||
            $responses[0]['state'] !== 'fulfilled'
        ) {
            return [];
        }

        $body = $responses[0]['value']
            ->getBody()
            ->getContents();

        return json_decode($body, true);

        return $responses;
    }


    public function peticionesUrlBatchSelectores(array $batch, string $webSite): array
    {

        $promises = [];
        foreach ($batch as $url) {

            if ($webSite == "https://perfumerias.com") {


                $promises = $this->peticionUrlPerfumerias($url, $promises);
            } elseif ($webSite == "https://www.perfumesclub.com") {

                $promises = $this->peticionUrlPrefumesClub($url, $promises);
            }
        }

        $responses = Utils::settle($promises)->wait();
        $resultados = [];

        foreach ($responses as $res) {
            if ($res['state'] === \GuzzleHttp\Promise\PromiseInterface::FULFILLED) {
                $body = $res['value']->getBody()->getContents();

                $data = json_decode($body, true);
                if ($data !== null) {
                    echo "🚨 BODY SCRAPER:\n" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
                } else {
                    echo "🚨 BODY SCRAPER (no es JSON): " . $body . "\n";
                }

                if (
                    $data === null ||
                    !isset($data['data']) ||
                    !is_array($data['data']) ||
                    empty($data['data'])
                ) {
                    echo "❌ Data vacía o sin selectores\n";
                    continue;
                }

                $resultados[] = $data['data'];
            } else {
                echo "❌ Error HTTP / Promise rechazada\n";
                $resultados[] = ['error' => $res['reason']];
            }
        }

        return $resultados;
    }


    function peticionUrlPerfumerias(string $url, array $promises): array
    {

        $marca = 'div.marca > a';
        $nombre = 'div.nombre';
        $precio = 'div.col-xs-4.col-sm-4.col-md-3.col-lg-2 > div.precio';
        $urlImagen = 'img#imagen_principal';
        $descripcion = 'div.tab-pane.active';
        $concentracion = 'div.subtitulo';
        $contenido = 'div.nombre_corto';
        $imgUrlContenido = 'img.img-responsive.img_mini.img_propia';

        $promises[] = $this->client->postAsync($this->scraperUrl, [
            'json' => [
                'url' => $url,
                'selector' => "",
                'selectors' => [
                    'marca' => $marca,
                    'nombre' => $nombre,
                    'precio' => $precio,
                    'url_imagen' => $urlImagen,
                    'descripcion' => $descripcion,
                    'concentracion' => $concentracion,
                    'contenido' => $contenido,
                    'imagen_url_contenido' => $imgUrlContenido,

                ],
            ]
        ]);

        return $promises;
    }


    function peticionUrlPrefumesClub(string $url, array $promises): array
    {

        $marca = 'h1.titleProduct > a';
        $nombre = 'h1.titleProduct > span';
    
        $descripcion = 'div#descriptionPFCPropio';
        $concentracion = 'h2.titleProduct';
     
 
        $promises[] = $this->client->postAsync($this->scraperUrl, [
            'json' => [
                'url' => $url,
                'selector' => "",
                'selectors' => [
                    'marca' => $marca,
                    'nombre' => $nombre,
                    'precio' => "",
                    'url_imagen' => "",
                    'descripcion' => $descripcion,
                    'concentracion' => $concentracion,
                   'contenido' => "",

                ],
            ]
        ]);

        return $promises;
    }
}
