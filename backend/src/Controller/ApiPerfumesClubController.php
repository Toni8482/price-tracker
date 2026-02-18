<?php

namespace App\Controller;

use App\Repository\PerfumesClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiPerfumesClubController extends AbstractController
{
    #[Route('/api/perfumes/club', name: 'app_api_perfumes_club')]
    public function index(PerfumesClubRepository $perfumesClubRepository): JsonResponse
    {
        $perfumes = $perfumesClubRepository->findAll();
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

    #[Route('/api/perfumes/club/{id}', name: 'api_perfumes_club_detail', methods: ['GET'])]
    public function show(PerfumesClubRepository $perfumesClubRepository, int $id): JsonResponse
    {
        $perfume = $perfumesClubRepository->find($id);

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
