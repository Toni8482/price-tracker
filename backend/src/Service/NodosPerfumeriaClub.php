<?php

namespace App\Service;

use App\Service\FormatterService;
use DOMDocument;

use App\Repository\StoresRepository;

class NodosPerfumeriaClub
{

    public function __construct(private StoresRepository $storesRepository, private FormatterService $formatterService) {}


    public function saveComponentsPerfumesClub(string $html, string $category): array
    {
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
            $cleanUrl = trim($enlaceProduct, "\" \n\r\t\\");

            if (!preg_match('#^https?://#', $cleanUrl)) {
                $cleanUrl = 'https://www.perfumesclub.com/' . ltrim($cleanUrl, '/');
            }

            if ($cleanUrl != "https://www.perfumesclub.com/")
                $urls[] = $cleanUrl;
        }



        return $urls;
    }


    public function saveComponentsDetallesPerfumesClub(array $responses): array
    {
        $respuesta = [];


        foreach ($responses as $url => $res) {
            if ($res['state'] === 'fulfilled') {

                $html = (string) $res['value']->getBody();

                libxml_use_internal_errors(true);
                $dom = new DOMDocument();
                $dom->loadHTML($html);
                $xpath = new \DOMXPath($dom);


                $marca = $xpath->query("//h1[contains(@class,'titleProduct')]/a")->item(0)->textContent;
                $nombre = $xpath->query("//h1[contains(@class,'titleProduct')]/span")->item(0)->textContent;
                $precio = $xpath->query("//div[contains(@class,'contPrecioNuevo')]")->item(0)->textContent;

                $div = $xpath->query("//div[contains(@id,'descriptionPFCPropio')]")->item(0);

                $innerHtml = '';
                if ($div) {
                    // Iteramos sobre los nodos hijos del div
                    foreach ($div->childNodes as $child) {
                        $innerHtml .= $dom->saveHTML($child);
                    }
                }

                /** @var \DOMElement $imagen */
                $imagen = $xpath->query("//img[contains(@class,'zoom')]")->item(0);
                $img = $imagen->getAttribute('src');
                $store = $this->storesRepository->find(2);


               

                // Contenido
                $nodoContenido = $xpath->query("//div[contains(@class,'font-16 font-w-700 tM1')]")->item(0);
                $contenido = $nodoContenido instanceof \DOMElement ? trim($nodoContenido->textContent) : '';

                // Concentración
                $nodoConcentracion = $xpath->query("//h2[contains(@class,'titleProduct')]")->item(0);
                $partesNodoConcentracion = [];

                if ($nodoConcentracion instanceof \DOMElement) {
                    $partesNodoConcentracion = explode("-", $nodoConcentracion->textContent);
                }

                $concentracion = trim($partesNodoConcentracion[0] ?? '');




                $respuesta[] = [
                    'marca' => $marca,
                    'nombre' => $nombre,
                    'precio' => $precio,
                    'url_imagen' => $img,
                    'url_producto' =>  $url,
                    'descripcion' =>  $innerHtml,
                    'tienda' => $store,
                    'contenido' => $contenido,
                    'concentracion' => $concentracion,

                ];

                $respuesta = $this->formatterService->responseFormat($respuesta);
                $this->formatterService->consoleExitFormat($respuesta);
            }
        }


        return $respuesta;
    }
}
