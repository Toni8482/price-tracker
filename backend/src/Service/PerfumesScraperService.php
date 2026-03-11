<?php

namespace App\Service;

use App\Service\PerfumesServices;
use App\Service\PeticionesUrlsService;
use App\Service\FormatterService;


class PerfumesScraperService
{
    public function __construct(
        private PerfumesServices $perfumesServices,
        private PeticionesUrlsService $peticionesUrlsService,
        private FormatterService $formatterService,

    ) {}


    public function scrape(array $webSites, string $category): void
    {
        try {

            $contadorProductos = 0;
            echo "####count(): " . count($webSites) . PHP_EOL;

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

                $allResults = $this->formatterService->responseFormat($allResults,  $webSites[$i]['base_url']);

                foreach ($allResults as $res) {

                    $contadorProductos += 1;
                }
                $this->formatterService->consoleExitFormat($allResults);

                $this->perfumesServices->savePerfumes($allResults, $webSites[$i]['base_url'], $webSites[$i]['publico']);
            }
            echo "❤️ Cantida de productos: " . $contadorProductos . " unidades" . PHP_EOL;
        } catch (\Exception $e) {
            echo "❌ Error al scrapear Perfumerias: " . $e->getMessage() . "\n";
        }
    }
}
