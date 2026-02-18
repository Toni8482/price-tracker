<?php

namespace App\Service;

use App\Entity\PerfumesClub;
use App\Repository\PCComponentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use DOMDocument;

class PerfumesClubScraper
{
    private string $scraperUrl = 'http://scraper-service:3000';
    private array $processedUrls = []; // Rastrear URLs en esta ejecución

    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $em,
        private PCComponentRepository $componentRepository
    ) {}

    public function scrape(string $url, string $category): void
    {
        echo "⏳ Iniciando scraping...\n";
        echo "   URL: $url\n";
        echo "   Categoría: $category\n";

        try {
            // Llamada al microservicio
            $response = $this->client->request('POST', $this->scraperUrl . '/scrape', [
                'json' => ['url' => $url]
            ]);

            $data = $response->toArray(); // Esto debería ser JSON
            if (empty($data)) {
                echo "⚠️ No se recibió ningún dato del microservicio\n";
                return;
            }

            $urls =  $this->saveComponentsFromJson($data['html'], $category);

          

            $productos = $this->saveComponentsDetalles($urls);

            foreach ($productos as $producto) {

                // Evitar duplicados por URL
                if ($this->componentRepository->findByUrl($producto['url_producto'])) {
                    continue;
                }



                $precioRaw = $producto['precio'];

                $precioLimpio = preg_replace('/[^0-9,]/', '', $precioRaw);
                $precio = (float) str_replace(',', '.', $precioLimpio);

                $pcComponent = new PerfumesClub();

                $pcComponent->setName(substr($producto['nombre'], 0, 255));
                $pcComponent->setPrice($precio);
                $pcComponent->setCategory($category);
                $pcComponent->setImageUrl($producto['url_imagen']);
                $pcComponent->setPerfumeUrl($producto['url_producto']);
                $pcComponent->setDescription(substr($producto['nombre'], 0, 500));
                $pcComponent->setStock(0);
             

                $this->em->persist($pcComponent);
            }

            $this->em->flush();
          
        } catch (\Exception $e) {
            echo "❌ Error al scrapear: " . $e->getMessage() . "\n";
        }
    }

    private function saveComponentsFromJson(string $html, string $category): array
    {


        // Depuración

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);

        $urls = [];
        // 3️⃣ Extraer datos
        $products = $xpath->query("//div[contains(@id,'ajaxPage')]/div");

        foreach ($products as $product) {
            /** @var \DOMElement $product */
            $enlaceProduct = $product->getElementsByTagName('a')->item(0)?->getAttribute('href');


            if ($enlaceProduct) {
                echo "https://www.perfumesclub.com/" . $enlaceProduct . "\n";
                $urls[] = "https://www.perfumesclub.com/" .$enlaceProduct; // ✅ Añadimos al array
            }
        }
        return  $urls;
    }


    private function saveComponentsDetalles(array $urls): array
    {
        $respuesta = [];
        foreach ($urls as $url) {

            // Depuración


            try {
                // Llamada al microservicio
                $response = $this->client->request('POST', $this->scraperUrl . '/scrape', [
                    'json' => ['url' => $url]
                ]);

                $data = $response->toArray(); // Esto debería ser JSON
                if (empty($data)) {
                    echo "⚠️ No se recibió ningún dato del microservicio\n";
                    continue;
                }


                $html = $data['html'];

                libxml_use_internal_errors(true);
                $dom = new DOMDocument();
                $dom->loadHTML($html);
                $xpath = new \DOMXPath($dom);
            //    $product = $xpath->query("//div[contains(@class,'section-wC9Kbw')]/a");

               // $detalle = $product->item(1);
                $nombre = $xpath->query("//h1[contains(@class,'titleProduct')]")->item(0)->textContent;
                $precio = $xpath->query("//div[contains(@class,'contPrecioNuevo')]")->item(0)->textContent;
          



              



                /** @var \DOMElement $imagen */
                $imagen = $xpath->query("//img[contains(@class,'zoom')]")->item(0);
                $img = $imagen->getAttribute('src');


                echo "Producto detalle\n";
                echo "Nombre: ";
                echo $nombre . "\n";
                echo "Precio: ";
                echo $precio . "\n";

                echo "Imagen: ";
                echo $img . "\n";


                $respuesta[] = [
                    'nombre' => $nombre,
                    'precio' => $precio,
                    'url_imagen' => $img,
                    'url_producto' => $url,

                ];
            } catch (\Exception $e) {
                echo "❌ Error al scrapear: " . $e->getMessage() . "\n";
            }
        }
        return $respuesta;
    }
}
