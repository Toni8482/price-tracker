<?php

namespace App\Controller;

use App\Repository\StoresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiStoresController extends AbstractController
{
    #[Route('/all/stores', name: 'app_api_stores')]
    public function stores(StoresRepository $storesRepository): JsonResponse
    {

        $stores = $storesRepository->findAll();
        $data = [];

        foreach ($stores as $store) {
            $data[] = [
                'id' => $store->getId(),
                'name' => $store->getName(),
                'url' =>$store->getBaseUrl(),
                'logo' =>$store->getLogo()
            ];
        }

        return $this->json($data);
    }
}
