<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use DOMDocument;

final class ProductCurlController extends AbstractController
{

    function getApiData(string $url): void
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        var_dump($response);
    }


    #[Route('/product/curl', name: 'app_product_curl')]
    public function index(): JsonResponse
    {

        $apiUrl = 'http://books.toscrape.com/index.html';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);


        $respuesta = [];
        $base = 'http://books.toscrape.com/';
        // 2️⃣ Parsearlo
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($response);
        $xpath = new \DOMXPath($dom);
        // 3️⃣ Extraer datos
        $products = $xpath->query("//article[contains(@class,'product_pod')]");

        foreach ($products as $product) {
            /** @var \DOMElement $product */
            $enlaceProduct = $product->getElementsByTagName('div')->item(0)->getElementsByTagName('a')->item(0)->getAttribute('href');
            $titleNode = $product->getElementsByTagName('h3')->item(0)->getElementsByTagName('a')->item(0);
            $href = $titleNode->getAttribute('href');

            $url = str_starts_with($href, 'http')
                ? $href
                : $base . ltrim($href, './');
            $text = trim($titleNode->textContent);

            $enlaceProduct = $base . ltrim($enlaceProduct, './');


            $priceNode = $product->getElementsByTagName('p');
            $precio = '';
            foreach ($priceNode as $p) {
                if ($p->getAttribute('class') === 'price_color') {
                    $precio = trim($p->textContent);
                    break;
                }
            }

            $stock = '';
            $stockNode = $product->getElementsByTagName('p');
            foreach ($stockNode as $stockN) {
                if ($stockN->getAttribute('class') == 'instock availability') {

                    $stock = trim($stockN->textContent);
                }
            }






            $apiUrlProduct = $enlaceProduct;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $apiUrlProduct);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);

            libxml_use_internal_errors(true);
            $dom = new DOMDocument();
            $dom->loadHTML($response);
            $xpath = new \DOMXPath($dom);









            $genreNode = $xpath->query("//div[contains(@class,'page_inner')]//ul/li[3]/a");
            if ($genreNode->length > 0) {
                $genero = trim($genreNode->item(0)->textContent);
            }

            $tituloNode = $xpath->query("//div[contains(@class,'product_main')]//h1");
            $titulo = $tituloNode->item(0)->textContent;

            $precioNode = $xpath->query("//div[contains(@class,'product_main')]//p[contains(@class,'price_color')]");
            $precio = $precioNode->item(0)->textContent;



            $urlImageNode = $xpath->query("//div[contains(@class,'item')]//img");
            /** @var \DOMElement $img */
            $img = $urlImageNode->item(0);
            // Obtienes el atributo 'src'
            $urlImage = $img->getAttribute('src');
            $urlImage = $base . ltrim($urlImage, './');

            $stockNode = $xpath->query("//p[contains(@class,'instock availability')]");
            $stock = $stockNode->item(0)->textContent;
            $stock = trim($stock);
            $descripcionNode = $xpath->query("//article[contains(@class,'product_page')]/p[1]");
            $descripcion = $descripcionNode->item(0)->textContent;

            $rating = null;

            $ratingNodes = $xpath->query("//p[contains(@class,'star-rating')]");

            if ($ratingNodes->length > 0 && $ratingNodes->item(0) instanceof \DOMElement) {

                /** @var \DOMElement $node */
                $node = $ratingNodes->item(0);
                $class = $node->getAttribute('class');

                $map = [
                    'One' => 1,
                    'Two' => 2,
                    'Three' => 3,
                    'Four' => 4,
                    'Five' => 5,
                ];

                foreach ($map as $word => $value) {
                    if (str_contains($class, $word)) {
                        $rating = $value;
                        break;
                    }
                }
            }


            $respuesta[] = [
                'tipo' => 'Product',
                'enlace_product' => $enlaceProduct,
                'titulo' => $titulo,
                'genero' =>  $genero,
                'url_image' => $urlImage,
                'precio' => $precio,
                'stock' => $stock,
                'descripcion' => $descripcion,
                'rating' =>   $rating
            ];
        }



        return $this->json([
            'response' => $respuesta,

        ]);
    }
}
