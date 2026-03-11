<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\UserService;
use App\Entity\User;
use App\Entity\Perfumes;
use App\Repository\PrecioContenidoRepository;


final class ApiFavoritesController extends AbstractController
{


    /**
     * Guardar perfume favorito
     */
    #[Route('/api/favorites/{id}', name: 'app_api_favorites', methods: ['POST'])]
    public function favorites(Perfumes $perfume, UserService $userService): JsonResponse
    {
        $user = $this->getUser();
        $userService->saveFavorites($user, $perfume);

        return $this->json([
            'message' => 'Favorito guardado',

        ]);
    }


    /**
     * Elimininar perfume favorito
     */
    #[Route('/api/favorites/{id}', name: 'delete_api_favorites', methods: ['DELETE'])]
    public function deleteFavorites(Perfumes $perfume, UserService $userService): JsonResponse
    {
        $user = $this->getUser();
        $userService->deleteFavorites($user, $perfume);

        return $this->json([
            'message' => 'Favorito eliminado',

        ]);
    }



    /**
     * Listar perfumes favoritos de usuario
     */
    #[Route('/api/favorites/users', name: 'api_favorites_users', methods: ['GET'])]
    public function favoritesUsers(PrecioContenidoRepository $precioContenidoRepository): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $perfumesFavoritos = $user->getPerfumes();
        $data = [];


        foreach ($perfumesFavoritos as $perfumeFavorito) {


            $precioContenido = $precioContenidoRepository->findByPerfumeId($perfumeFavorito);

            $result = [];
            foreach ($precioContenido as $preCont) {

                $result[] = [

                    "precio" => $preCont->getPrecio(),
                    "contenido" => $preCont->getContenido(),
                    "image_url_precio_contenido" => $preCont->getImageUrl(),

                ];
            }

            $data[] = [
                "id" => $perfumeFavorito->getId(),
                "marca" => $perfumeFavorito->getBrand(),
                "nombre" => $perfumeFavorito->getName(),
                "perfume_url" => $perfumeFavorito->getPerfumeUrl(),
                "imagen_url" => $perfumeFavorito->getImageUrl(),
                "store_id" => $perfumeFavorito->getStore()->getId(),
                "store_name" => $perfumeFavorito->getStore()->getName(),
                "store_url" => $perfumeFavorito->getStore()->getBaseUrl(),
                "store_logo" => $perfumeFavorito->getStore()->getLogo(),
                "concentracion" => $perfumeFavorito->getConcentracion(),
                "target_public" => $perfumeFavorito->getTargetPublic()->getName(),
                "descripcion" => $perfumeFavorito->getDescription(),
                "precio_contenido" => $result,
            ];
        }
        return $this->json($data);
    }
}
