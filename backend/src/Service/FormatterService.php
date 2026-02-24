<?php
namespace App\Service;

class FormatterService 
{
    
public function __construct() {
   
}


    public function consoleExitFormat(array $perfumes)
    {

        foreach ($perfumes as $perfume) {

            echo "\033[32m##############Producto detalle de perfume##############\033[0m\n";

            echo "\033[33mMarca: \033[0m";
            echo $perfume['marca'] . "\n";
            echo "\033[34mNombre: \033[0m";
            echo $perfume['nombre'] . "\n";
            echo "\033[35mPrecio: \033[0m";
            echo $perfume['precio'] . "\n";
            echo "\033[36mImagen: \033[0m";
            echo $perfume['url_imagen'] . "\n";
            echo "\033[33mUrl producto: \033[0m";
            echo $perfume['url_producto'] . "\n";
            echo "\033[45m Contenido: \033[0m";
            echo $perfume['contenido'] . "\n";
            echo "\033[78m Concentracion: \033[0m";
            echo $perfume['concentracion'] . "\n";
            echo "\033[34mDescripcion: \033[0m";
            echo $perfume['descripcion'] . "\n";
        }
    }

    public function responseFormat(array $response): array
    {

        foreach ($response as $res) {

            $descriptionHtml = str_replace(["\\n", "\\r", "\\t"], '',  $res['descripcion']);
            $descriptionHtml = str_replace('/\s+/', ' ', $descriptionHtml);
            $descriptionHtml = trim($descriptionHtml);
            $precioRaw = $res['precio'];
            $precioLimpio = preg_replace('/[^0-9,]/', '', $precioRaw);
            $precio = (float) str_replace(',', '.', $precioLimpio);

            $cleanUrl = trim($res['url_imagen'], "\" \n\r\t\\");
 $contenido = str_replace(["\\n", "\\r", "\\t"], '',  $res['contenido']);
 $contenido = trim($contenido);
 $concentracion= str_replace(["\\n", "\\r", "\\t"], '',  $res['concentracion']);
 $concentracion = trim($concentracion);



            $respuesta[] = [
                'marca' => $res['marca'],
                'nombre' => $res['nombre'],
                'precio' => $precio,
                'url_imagen' => $cleanUrl,
                'url_producto' => $res['url_producto'],
                'descripcion' => $descriptionHtml,
                'tienda' => $res['tienda'],
                  'contenido' => $contenido,
                    'concentracion' => $concentracion,

            ];
        }
        return $respuesta;
    }

}
