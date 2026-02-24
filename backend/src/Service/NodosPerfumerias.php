<?php

namespace App\Service;

use App\Service\FormatterService;
use DOMDocument;

use App\Repository\StoresRepository;

class NodosPerfumerias
{

    public function __construct(private StoresRepository $storesRepository, private FormatterService $formatterService) {}


    public  function saveComponentsPerfumerias(string $html, string $category): array
    {
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

            // Quitar comillas, espacios, saltos de línea y barras invertidas al final
            $cleanUrl = trim($enlaceProduct, "\" \n\r\t\\");
            $urls[] = $cleanUrl;

            if (!preg_match('#^https?://#', $cleanUrl)) {
                $cleanUrl = 'https://perfumerias.com/' . ltrim($cleanUrl, '/');
            }
        }


        return $urls;
    }


    public  function saveComponentsDetallesPerfumerias(array $responses): array
    {
        $respuesta = [];



        foreach ($responses as $url => $res) {
            if ($res['state'] === 'fulfilled') {

                $html = (string) $res['value']->getBody();

                libxml_use_internal_errors(true);
                $dom = new DOMDocument();
                $dom->loadHTML($html);
                $xpath = new \DOMXPath($dom);




                /** @var \DOMElement  $nodeNombre */
                $marca = $xpath->query("//div[contains(@class,'marca')]/a")->item(0)->textContent;
                $nombre = $xpath->query("//div[contains(@class,'nombre')]")->item(0)->textContent;

                $precio = $xpath->query("//div[contains(@class,'precio')]")->item(0)->textContent;

                $div = $xpath->query("//div[contains(@class,'tab-pane')]")->item(0);
                /** @var \DOMElement $imagen */
                $imagen = $xpath->query("//img[contains(@id,'imagen_principal')]")->item(0);
                $img =  $imagen->getAttribute('src');
                $cleanUrl = "https://perfumerias.com" . trim($img, "\" \n\r\t\\");
                $store = $this->storesRepository->find(1);

                $innerHtml = '';
                if ($div) {
                    // Iteramos sobre los nodos hijos del div
                    foreach ($div->childNodes as $child) {
                        $innerHtml .= $dom->saveHTML($child);
                    }
                }


                $nodoContenido = $xpath->query("//div[contains(@class,'nombre_corto')]")->item(0)->textContent;

                $contenido = trim($nodoContenido);
                $nodoConcentracion = $xpath->query("//div[contains(@class,'subtitulo')]")->item(0)->textContent;
                $partesNodoConcentracion = explode("|", $nodoConcentracion);
                if (count($partesNodoConcentracion) > 1) {
                    $concentracion = trim($partesNodoConcentracion[0]);
                } else {
                    $concentracion = '';
                }




                $respuesta[] = [
                    'marca' => $marca,
                    'nombre' => $nombre,
                    'precio' => $precio,
                    'url_imagen' => $cleanUrl,
                    'url_producto' => $url,
                    'descripcion' => $innerHtml,
                    'tienda' => $store,
                    'contenido' => $contenido,
                    'concentracion' => $concentracion,
                ];
                $respuesta = $this->formatterService->responseFormat($respuesta);
                $this->formatterService->consoleExitFormat($respuesta);
            } else {

                echo "Error en scraping: " . $res['reason'] . "\n";
            }
        }


        return $respuesta;
    }
}
