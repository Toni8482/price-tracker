<?php

namespace App\Controller;

use App\Repository\PerfumesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiPerfumesController extends AbstractController
{
    #[Route('/api/perfumes', name: 'app_api_perfumes')]
    public function index(PerfumesRepository $perfumesRepository): JsonResponse
    {
        $perfumes = $perfumesRepository->findAll();
        $datos = [];
        foreach ($perfumes as $perfume) {
            $datos[] = [
                "id" => $perfume->getId(),
                "marca" => $perfume->getBrand(),
                "nombre" => $perfume->getName(),
                "precio" => $perfume->getPrice(),
                "perfume_url" => $perfume->getPerfumeUrl(),
                "imagen_url" => $perfume->getImageUrl(),
                "stock" => $perfume->getStock(),
                "descripcion" => $perfume->getDescription(),
                "store_id" => $perfume->getStore()->getId(),
                "store_name"=> $perfume->getStore()->getName(),
                "store_url"=> $perfume->getStore()->getBaseUrl(),
                "store_logo"=> $perfume->getStore()->getLogo(),
                 "contenido"=> $perfume->getContenido(),
                "concentracion"=> $perfume->getConcentracion(),
            ];
        }

        return $this->json([
            'datos' =>  $datos,

        ]);
    }


     #[Route('/api/perfumes/{id}', name: 'api_perfumes_detail', methods: ['GET'])]
    public function show(PerfumesRepository $perfumesRepository, int $id): JsonResponse
    {
        $perfume = $perfumesRepository->find($id);

        if (!$perfume) {
            return new JsonResponse(['error' => 'Perfume no encontrado'], 404);
        }

        $data = [
             "id" => $perfume->getId(),
                "marca" => $perfume->getBrand(),
                "nombre" => $perfume->getName(),
                "precio" => $perfume->getPrice(),
                "perfume_url" => $perfume->getPerfumeUrl(),
                "imagen_url" => $perfume->getImageUrl(),
                "stock" => $perfume->getStock(),
                "descripcion" => $perfume->getDescription(),
                "store_id" => $perfume->getStore()->getId(),
                "store_name"=> $perfume->getStore()->getName(),
                "store_url"=> $perfume->getStore()->getBaseUrl(),
                "store_logo"=> $perfume->getStore()->getLogo(),
                "contenido"=> $perfume->getContenido(),
                "concentracion"=> $perfume->getConcentracion(),
        ];

        return new JsonResponse($data);
    }
}
