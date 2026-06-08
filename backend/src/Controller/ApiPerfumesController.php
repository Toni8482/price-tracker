<?php

namespace App\Controller;

use App\Repository\PerfumesRepository;
use App\Repository\PrecioContenidoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class ApiPerfumesController extends AbstractController
{

    /**
     * Listar todos los perfumes
     */
    #[Route('/api/perfumes', name: 'app_api_perfumes')]
    public function index(
        PerfumesRepository $perfumesRepository,
        PrecioContenidoRepository $precioContenidoRepository,

    ): JsonResponse {
        $perfumes = $perfumesRepository->findAll();

        $datos = [];
        foreach ($perfumes as $perfume) {

            $precioContenido = $precioContenidoRepository->findByPerfumeId($perfume);

            $result = [];
            foreach ($precioContenido as $preCont) {

                $result[] = [
                    "id_contenido" => $preCont->getId(),
                    "precio" => $preCont->getPrecio(),
                    "contenido" => $preCont->getContenido(),
                    "image_url_precio_contenido" => $preCont->getImageUrl(),
                ];
            }

            $datos[] = [
                "id" => $perfume->getId(),
                "marca" => $perfume->getBrand(),
                "nombre" => $perfume->getName(),
                "perfume_url" => $perfume->getPerfumeUrl(),
                "imagen_url" => $perfume->getImageUrl(),
                "store_id" => $perfume->getStore()->getId(),
                "store_name" => $perfume->getStore()->getName(),
                "store_url" => $perfume->getStore()->getBaseUrl(),
                "store_logo" => $perfume->getStore()->getLogo(),
                "concentracion" => $perfume->getConcentracion(),
                "target_public" => $perfume->getTargetPublic()->getName(),
                "descripcion" => $perfume->getDescription(),
                "precio_contenido" => $result,
            ];
        }

        return $this->json([
            'datos' =>  $datos,
        ]);
    }

    /**
     * Detalles de un perfume buscandolo por su id
     */
    #[Route('/api/perfumes/{id}', name: 'api_perfumes_detail', methods: ['GET'])]
    public function show(PerfumesRepository $perfumesRepository, PrecioContenidoRepository $precioContenidoRepository, int $id): JsonResponse
    {
        $perfume = $perfumesRepository->find($id);

        if (!$perfume) {
            return new JsonResponse(['error' => 'Perfume no encontrado'], 404);
        }

        $precioContenido = $precioContenidoRepository->findByPerfumeId($perfume);

        $result = [];
        foreach ($precioContenido as $preCont) {

            $result[] = [
                "id_contenido" => $preCont->getId(),
                "precio" => $preCont->getPrecio(),
                "contenido" => $preCont->getContenido(),
                "image_url_precio_contenido" => $preCont->getImageUrl(),
            ];
        }

        $data = [
            "id" => $perfume->getId(),
            "marca" => $perfume->getBrand(),
            "nombre" => $perfume->getName(),
            "perfume_url" => $perfume->getPerfumeUrl(),
            "imagen_url" => $perfume->getImageUrl(),
            "store_id" => $perfume->getStore()->getId(),
            "store_name" => $perfume->getStore()->getName(),
            "store_url" => $perfume->getStore()->getBaseUrl(),
            "store_logo" => $perfume->getStore()->getLogo(),
            "concentracion" => $perfume->getConcentracion(),
            "descripcion" => $perfume->getDescription(),
            "precio_contenido" => $result,
        ];

        return new JsonResponse($data);
    }

    #[Route('/perfumes/page', methods: ['GET'])]
    public function perfumesPage(
        Request $request,
        PerfumesRepository $perfumesRepository,
        PrecioContenidoRepository $precioContenidoRepository
    ): JsonResponse {

        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 20;

        $perfumes = $perfumesRepository->findBy(
            [],
            ['id' => 'ASC'],
            $limit,
            ($page - 1) * $limit
        );

        $datos = [];

      
        foreach ($perfumes as $perfume) {

            $precioContenido = $precioContenidoRepository->findByPerfumeId($perfume);

            $result = [];
            foreach ($precioContenido as $preCont) {

                $result[] = [
                    "id_contenido" => $preCont->getId(),
                    "precio" => $preCont->getPrecio(),
                    "contenido" => $preCont->getContenido(),
                    "image_url_precio_contenido" => $preCont->getImageUrl(),
                ];
            }

            $datos[] = [
                "id" => $perfume->getId(),
                "marca" => $perfume->getBrand(),
                "nombre" => $perfume->getName(),
                "perfume_url" => $perfume->getPerfumeUrl(),
                "imagen_url" => $perfume->getImageUrl(),
                "store_id" => $perfume->getStore()->getId(),
                "store_name" => $perfume->getStore()->getName(),
                "store_url" => $perfume->getStore()->getBaseUrl(),
                "store_logo" => $perfume->getStore()->getLogo(),
                "concentracion" => $perfume->getConcentracion(),
                "target_public" => $perfume->getTargetPublic()->getName(),
                "descripcion" => $perfume->getDescription(),
                "precio_contenido" => $result,
            ];
        }

        return $this->json([
            'page' => $page,
            'datos' => $datos
        ]);
    }
}
