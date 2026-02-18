<?php

namespace App\Controller;

use App\Repository\PerfumesClubRepository;
use App\Repository\PerfumeriasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiAllPerfumesController extends AbstractController
{
    #[Route('/api/all/perfumes', name: 'app_api_all_perfumes')]
    public function index(PerfumeriasRepository $perfumeriasRepository, PerfumesClubRepository $perfumesClubRepository): JsonResponse
    {

        $perfumerias = $perfumeriasRepository->findAll();
        $perfumesClub = $perfumesClubRepository->findAll();
        $datos = [];

        foreach (array_merge($perfumerias, $perfumesClub) as $p) {
          
                $datos[] = [
                    'id' => $p->getId(),
                    'nombre' => $p->getName(),
                    'precio' => $p->getPrice(),
                    'url_image' => $p->getImageUrl(),
                    'stock' => $p->getStock(),
                ];
           
        }
        return $this->json([
            'perfumes' => $datos,

        ]);
    }
}
