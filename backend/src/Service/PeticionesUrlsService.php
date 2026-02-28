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


        // 👇 AQUÍ ESTABA EL PROBLEMA
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
                $promises[] = $this->client->postAsync($this->scraperUrl, [
                    'json' => [
                        'url' => $url,
                        'selector' => "",
                        'selectors' => [
                            'marca' => 'div.marca > a',
                            'nombre' => 'div.nombre',
                            'precio' => 'div.col-xs-4.col-sm-4.col-md-3.col-lg-2 > div.precio',
                            'url_imagen' => 'img#imagen_principal',
                            'descripcion' => 'div.tab-pane.active',
                            'concentracion' => 'div.subtitulo',
                            'contenido' => 'div.nombre_corto',

                        ],
                    ]
                ]);
            } elseif ($webSite == "https://www.perfumesclub.com") {
                $promises[] = $this->client->postAsync($this->scraperUrl, [
                    'json' => [
                        'url' => $url,
                        'selector' => "",
                        'selectors' => [
                            'marca' => 'h1.titleProduct > a',
                            'nombre' => 'h1.titleProduct > span',
                            'precio' => 'div.contPrecioNuevo',
                            'url_imagen' => 'img.zoom',
                            'descripcion' => 'div#descriptionPFCPropio',
                            'concentracion' => 'h2.titleProduct',
                            'contenido' => 'div.font-16.font-w-700.tM1',


                        ],
                    ]
                ]);
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

                $marca  = $data['data']['marca'][0]['text'] ?? '—';
                $urlProducto  = $data['data']['url'] ?? '—';
                $nombre = $data['data']['nombre'][0]['text'] ?? '—';
                $urlScrapeada = $data['url'] ?? '—';
                $precioRaw = $data['data']['precio'][0]['text'] ?? '—';
                $urlImagen = $data['data']['url_imagen'][0]['src'] ?? '—';
                if (!preg_match('#^https?://#i', $urlImagen)) {
                    $cleanUrl = rtrim($webSite, '/') . '/' . ltrim($urlImagen, '/');
                } else {
                    $cleanUrl = $urlImagen;
                }

                $descripcion = $data['data']['descripcion'][0]['html'] ?? '—';
                $concentracion = $data['data']['concentracion'][0]['text'] ?? '—';
                $partesNodoConcentracion = explode("|", $concentracion);
                if (count($partesNodoConcentracion) > 1) {
                    $concentracion = trim($partesNodoConcentracion[0]);
                } 



                $contenido = $data['data']['contenido'][0]['text'] ?? '—';
                $contenido = trim($contenido);


                echo PHP_EOL;
                echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;
                echo "🌐 URL            : {$urlProducto}" . PHP_EOL;
                echo "🏷️ Marca          : {$marca}" . PHP_EOL;
                echo "🧴 Nombre         : {$nombre}" . PHP_EOL;
                echo "💰 Precio         : {$precioRaw}" . PHP_EOL;
                echo "🖼️ Imagen URL     : {$cleanUrl}" . PHP_EOL;
                echo "📝 Descripción    : {$descripcion}" . PHP_EOL;
                echo "⚗️ Concentración  : {$concentracion}" . PHP_EOL;
                echo "📦 Contenido      : {$contenido}" . PHP_EOL;
                echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;



   $precioLimpio = preg_replace('/[^0-9,]/', '', $precioRaw);
            $precio = (float) str_replace(',', '.', $precioLimpio);


             $contenido = str_replace(["\\n", "\\r", "\\t"], '',  $contenido);
 $contenido = trim($contenido);



                $resultados[] = [
                    'url' =>  $urlProducto,
                    'marca' => $marca,
                    'nombre' => $nombre,
                    'precio' => $precio,
                    'url_imagen' => $cleanUrl,
                    'descripcion' => $descripcion,
                    'concentracion' => $concentracion,
                    'contenido' => $contenido,
                    'url_producto' =>  $urlProducto,
                ];
            } else {
                echo "❌ Error HTTP / Promise rechazada\n";
                $resultados[] = ['error' => $res['reason']];
            }
        }




        return $resultados;
    }
}
