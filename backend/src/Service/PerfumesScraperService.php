<?php

namespace App\Service;

use App\Service\SaveBdPerfumes;
use App\Service\PeticionesUrlsService;
use App\Service\NodosPerfumerias;
use App\Service\NodosPerfumeriaClub;


class PerfumesScraperService
{
    public function __construct(
        private SaveBdPerfumes $saveBdPerfumes,
        private PeticionesUrlsService $peticionesUrlsService,
        private NodosPerfumerias $nodosPerfumerias,
        private NodosPerfumeriaClub $nodosPerfumeriaClub,
    ) {}


    public function scrape(array $webSites, string $category): void
    {


        try {

            $contadorProductos = 0;

           
 echo "####count(): " . count($webSites). PHP_EOL;
          
            for ($i = 0; $i < count($webSites); $i++) {
                $responses = $this->peticionesUrlsService
                    ->peticionesUrlSelectores($webSites[$i]['url'], $webSites[$i]['selector']);


                if (!isset($responses['urls']) || !is_array($responses['urls'])) {
                    continue;
                }

                foreach ($responses['urls'] as $res) {
                    echo "#### Url: " . $res . PHP_EOL;
                }



                $allResults = [];

                $chunks = array_chunk($responses['urls'], 12);
                foreach ($chunks as $batch) {
                    $batchResponses = $this->peticionesUrlsService->peticionesUrlBatchSelectores($batch, $webSites[$i]['base_url']);
                    $allResults = array_merge($allResults, $batchResponses);
                }

                // Recorrer todos los resultados de manera segura
                foreach ($allResults as $res) {
                    if (isset($res['error'])) {
                        echo "❌ Error scraping: " . $res['error'] . PHP_EOL;
                        continue;
                    }

                    echo "#### URL:    " . $res['url'] . PHP_EOL;
                    echo "#### Marca:  " . $res['marca'] . PHP_EOL;
                    echo "#### Nombre: " . $res['nombre'] . PHP_EOL;
                    $contadorProductos += 1;
                }


                $this->saveBdPerfumes->savePerfumes( $allResults, $webSites[$i]['base_url'], $webSites[$i]['publico']);
            }
            echo "❤️ Cantida de productos: " . $contadorProductos . " unidades" . PHP_EOL;
        } catch (\Exception $e) {
            echo "❌ Error al scrapear Perfumerias: " . $e->getMessage() . "\n";
        }









        /*
        try {

            foreach ($urls as $url) {

                $responses = $this->peticionesUrlsService->peticionesUrl($url);

                foreach ($responses as $res) {
                    if ($res['state'] === 'fulfilled') {

                        $html = (string) $res['value']->getBody();

                        $urls =   $this->nodosPerfumerias->saveComponentsPerfumerias($html, $category);
                        $chunks = array_chunk($urls, 12);
                        foreach ($chunks as $batch) {

                            $responses = $this->peticionesUrlsService->peticionesUrlBatch($batch);
                            $productos = $this->nodosPerfumerias->saveComponentsDetallesPerfumerias($responses);
                            $this->saveBdPerfumes->savePerfumes($productos);
                        }
                    } else {
                        // manejar errores
                        echo "Error en scraping: " . $res['reason'] . "\n";
                    }
                }
            }
        } catch (\Exception $e) {
            echo "❌ Error al scrapear Perfumerias: " . $e->getMessage() . "\n";
        }

        try {

            foreach ($responses as $res) {

                $html = (string) $res['value']->getBody();


                $urls =   $this->nodosPerfumeriaClub->saveComponentsPerfumesClub($html, $category);

                  $chunks = array_chunk($urls, 12);
                        foreach ($chunks as $batch) {

                            $responses = $this->peticionesUrlsService->peticionesUrlBatch($batch);
                            $productos = $this->nodosPerfumeriaClub->saveComponentsDetallesPerfumesClub($responses);
                            $this->saveBdPerfumes->savePerfumes($productos);
                        }
            }
        } catch (\Exception $e) {
            echo "❌ Error al scrapear PerfumesClub: " . $e->getMessage() . "\n";
        }

        */
    }
}
