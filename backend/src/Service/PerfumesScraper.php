<?php

namespace App\Service;

use App\Entity\Perfumes;
use App\Repository\StoresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use DOMDocument;

class PerfumesScraper
{
    private string $scraperUrl = 'http://scraper-service:3000';
    private array $processedUrls = []; // Rastrear URLs en esta ejecución

    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $em,
        private StoresRepository $storesRepository
    ) {}

    public function scrape(string $url1, string $url2, string $category): void
    {
        echo "⏳ Iniciando scraping...\n";
        echo "   URL: $url1\n";
        echo "   Categoría: $category\n";

        try {
            // Llamada al microservicio
            $response = $this->client->request('POST', $this->scraperUrl . '/scrape', [
                'json' => ['url' => $url1]
            ]);

            $data = $response->toArray(); // Esto debería ser JSON
            if (empty($data)) {
                echo "⚠️ No se recibió ningún dato del microservicio\n";
                return;
            }

            $urls =  $this->saveComponentsPerfumerias($data['html'], $category);



            $productos = $this->saveComponentsDetallesPerfumerias($urls);

            foreach ($productos as $producto) {





                $precioRaw = $producto['precio'];

                $precioLimpio = preg_replace('/[^0-9,]/', '', $precioRaw);
                $precio = (float) str_replace(',', '.', $precioLimpio);

                $store = $this->storesRepository->find(1);


                $pcComponent = new Perfumes();

                $pcComponent->setName(substr($producto['nombre'], 0, 255));
                $pcComponent->setPrice($precio);
                $pcComponent->setCategory($category);
                $pcComponent->setImageUrl($producto['url_imagen']);
                $pcComponent->setPerfumeUrl($producto['url_producto']);
                $pcComponent->setDescription(substr($producto['nombre'], 0, 500));
                $pcComponent->setStock(0);
                $pcComponent->setStore($store);


                $this->em->persist($pcComponent);
            }

            $this->em->flush();
        } catch (\Exception $e) {
            echo "❌ Error al scrapear: " . $e->getMessage() . "\n";
        }

        echo "⏳ Iniciando scraping...\n";
        echo "   URL: $url2\n";
        echo "   Categoría: $category\n";


        try {
            // Llamada al microservicio
            $response = $this->client->request('POST', $this->scraperUrl . '/scrape', [
                'json' => ['url' => $url2]
            ]);

            $data = $response->toArray(); // Esto debería ser JSON
            if (empty($data)) {
                echo "⚠️ No se recibió ningún dato del microservicio\n";
                return;
            }

            $urls =  $this->saveComponentsPerfumesClub($data['html'], $category);



            $productos = $this->saveComponentsDetallesPerfumesClub($urls);

            foreach ($productos as $producto) {





                $precioRaw = $producto['precio'];

                $precioLimpio = preg_replace('/[^0-9,]/', '', $precioRaw);
                $precio = (float) str_replace(',', '.', $precioLimpio);

                $store = $this->storesRepository->find(2);


                $pcComponent = new Perfumes();

                $pcComponent->setName(substr($producto['nombre'], 0, 255));
                $pcComponent->setPrice($precio);
                $pcComponent->setCategory($category);
                $pcComponent->setImageUrl($producto['url_imagen']);
                $pcComponent->setPerfumeUrl($producto['url_producto']);
                $pcComponent->setDescription(substr($producto['nombre'], 0, 500));
                $pcComponent->setStock(0);
                $pcComponent->setStore($store);

                $this->em->persist($pcComponent);
            }

            $this->em->flush();
        } catch (\Exception $e) {
            echo "❌ Error al scrapear: " . $e->getMessage() . "\n";
        }
    }

    private function saveComponentsPerfumerias(string $html, string $category): array
    {


        // Depuración

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);

        $urls = [];
        // 3️⃣ Extraer datos
        $products = $xpath->query("//div[contains(@class,'mini_contenedor_grupo')]/div/a");

        foreach ($products as $product) {
            /** @var \DOMElement $product */
            $enlaceProduct = $product->getAttribute('href');
            echo $enlaceProduct . "\n";
            $urls[] = $enlaceProduct; // ✅ Añadimos al array
        }
        return  $urls;
    }


    private function saveComponentsDetallesPerfumerias(array $urls): array
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
              //  $product = $xpath->query("//div[contains(@class,'section-wC9Kbw')]/a");

               // $detalle = $product->item(1);

                /** @var \DOMElement  $nodeNombre */
                $marca = $xpath->query("//div[contains(@class,'marca')]/a")->item(0)->textContent;
                $nombre = $xpath->query("//div[contains(@class,'nombre')]")->item(0)->textContent;
                $subtitulo = $xpath->query("//div[contains(@class,'subtitulo')]")->item(1)->textContent;
                $precio = $xpath->query("//div[contains(@class,'precio')]")->item(0)->textContent;



                $nombreCompleto = $marca . " " . $nombre;




                /** @var \DOMElement $imagen */
                $imagen = $xpath->query("//img[contains(@id,'imagen_principal')]")->item(0);
                $img = "https://perfumerias.com" . $imagen->getAttribute('src');


                echo "Producto detalle\n";
                echo "Nombre: ";
                echo $nombreCompleto . "\n";
                echo "Precio: ";
                echo $precio . "\n";

                echo "Imagen: ";
                echo $img . "\n";


                $respuesta[] = [
                    'nombre' => $nombreCompleto,
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



    private function saveComponentsPerfumesClub(string $html, string $category): array
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
                $urls[] = "https://www.perfumesclub.com/" . $enlaceProduct; // ✅ Añadimos al array
            }
        }
        return  $urls;
    }


    private function saveComponentsDetallesPerfumesClub(array $urls): array
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
