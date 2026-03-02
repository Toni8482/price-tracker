<?php

namespace App\Service;

class FormatterService
{

    public function __construct() {}


    public function consoleExitFormat(array $perfumes)
    {

        foreach ($perfumes as $perfume) {




            echo "\033[32m##############Producto detalle de perfume##############\033[0m\n";

            echo "\033[33mMarca: \033[0m";
            echo $perfume['marca'] . "\n";
            echo "\033[34mNombre: \033[0m";
            echo $perfume['nombre'] . "\n";

            foreach ($perfume['precio_contenido'] as $precioContenido) {

                echo "\033[35mPrecio: \033[0m";
                echo $precioContenido['precio'] . "\n";
                echo "\033[45m Contenido: \033[0m";
                echo $precioContenido['contenido'] . "\n";
                echo "\033[36mImagen: \033[0m";
                echo $precioContenido['image_url'] . "\n";
            }

            echo "\033[36mImagen: \033[0m";
            echo $perfume['url_imagen'] . "\n";
            echo "\033[33mUrl producto: \033[0m";
            echo $perfume['url_producto'] . "\n";


            echo "\033[78m Concentracion: \033[0m";
            echo $perfume['concentracion'] . "\n";
            echo "\033[34mDescripcion: \033[0m";
            echo $perfume['descripcion'] . "\n";
        }
    }

    public function responseFormat(array $response, string $webSite): array
    {

        foreach ($response as $data) {


            $marca  = $data['data']['marca'][0]['text'] ?? '—';
            $urlProducto  = $data['data']['url'] ?? '—';
            $nombre = $data['data']['nombre'][0]['text'] ?? '—';
            $urlScrapeada = $data['url'] ?? '—';

            $precios = $data['data']['precio'] ?? '—';



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


            $precioContenido = [];
            for ($i = 0; $i < count($precios); $i++) {

                $precioLimpio = preg_replace('/[^0-9,]/', '', $precios[$i]['text']);
                $precio = (float) str_replace(',', '.', $precioLimpio);

                $dataUrl = $data['data']['imagen_url_contenido'][$i]['src'] ?? '—';
                $url_img = '-';
                if ($dataUrl != '-') {
                    $url_img = rtrim($webSite, '/') . '/' . ltrim($dataUrl, '/');
                }



                $precioContenido[] = [
                    'precio' => $precio ?? '—',
                    'contenido' => $data['data']['contenido'][$i]['text'] ?? '—',
                    'image_url' => $url_img,
                ];
            };

            echo PHP_EOL;
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;
            echo "🌐 URL            : {$urlProducto}" . PHP_EOL;
            echo "🏷️ Marca          : {$marca}" . PHP_EOL;
            echo "🧴 Nombre         : {$nombre}" . PHP_EOL;
            //  echo "💰 Precio         : {$precioContenido}" . PHP_EOL;
            echo "🖼️ Imagen URL     : {$cleanUrl}" . PHP_EOL;
            echo "📝 Descripción    : {$descripcion}" . PHP_EOL;
            echo "⚗️ Concentración  : {$concentracion}" . PHP_EOL;
            echo "📦 Contenido      : {$contenido}" . PHP_EOL;
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;



            $contenido = str_replace(["\\n", "\\r", "\\t"], '',  $contenido);
            $contenido = trim($contenido);



            $respuesta[] = [
                'url' =>  $urlProducto,
                'marca' => $marca,
                'nombre' => $nombre,
                'precio_contenido' => $precioContenido,
                'url_imagen' => $cleanUrl,
                'descripcion' => $descripcion,
                'concentracion' => $concentracion,
                'contenido' => $contenido,
                'url_producto' =>  $urlProducto,
            ];
        }
        return $respuesta;
    }
}
