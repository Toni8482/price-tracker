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


    public function scrape(array $urls, string $category): void
    {

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
    }
}
