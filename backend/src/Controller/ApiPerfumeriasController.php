<?php

namespace App\Controller;

use App\Repository\PerfumeriasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiPerfumeriasController extends AbstractController
{
    #[Route('/api/perfumerias', name: 'app_api_perfumerias')]
    public function index(PerfumeriasRepository $perfumeriasRepository): JsonResponse
    {
        $perfumes = $perfumeriasRepository->findAll();
        foreach ($perfumes as $perfume) {
            $datos[] = [
                "id" => $perfume->getId(),
                "nombre" => $perfume->getName(),
                "precio" => $perfume->getPrice(),
                "url_perfume" => $perfume->getPerfumeUrl(),
                "stock" => $perfume->getStock(),
                "url_image" => $perfume->getImageUrl(),
                "descripcion" => $perfume->getDescription(),
                "categoria" => $perfume->getCategory()

            ];
        }



        return $this->json([

            'perfumes' => $datos,
        ]);
    }

    #[Route('/api/perfumerias/{id}', name: 'api_perfumerias_detail', methods: ['GET'])]
    public function show(PerfumeriasRepository $perfumeriasRepository, int $id): JsonResponse
    {
        $perfume = $perfumeriasRepository->find($id);

        if (!$perfume) {
            return new JsonResponse(['error' => 'Perfume no encontrado'], 404);
        }

        $data = [
            'id'    => $perfume->getId(),
            'nombre' => $perfume->getName(),
            'precio' => $perfume->getPrice(),
            'imagen_url' => $perfume->getImageUrl(),
        ];

        return new JsonResponse($data);
    }
}
