<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\SaveBdUsers;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;


final class ApiUsersController extends AbstractController
{
    #[Route('/users', name: 'app_api_users', methods: ['POST'])]
    public function index(Request $request, SaveBdUsers $SaveBdUsers): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        $SaveBdUsers->saveUser($data);

        return $this->json([
            'message' => 'Contenido del body',
            'user' =>  $data,
        ]);
    }


    #[Route('/api/favorites', name: 'app_api_favorites', methods: ['POST'])]
    public function favorites(Request $request, SaveBdUsers $SaveBdUsers): JsonResponse
    {
       
        $data = json_decode($request->getContent(), true);

        $SaveBdUsers->saveFavorites($data);

        return $this->json([
            'message' => 'Contenido del body',
            'user' =>  $data,
        ]);
    }




    #[Route('/api/headers', methods: ['GET'])]
    public function headers(Request $request)
    {
        return $this->json($request->headers->all());
    }


    #[Route('/api/me', name: 'api_me', methods: ['GET'])]
    public function me(#[CurrentUser] ?User $user): JsonResponse
    {
        if (!$user) {
            return $this->json([
                'message' => 'Token inválido o expirado',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getUserIdentifier(),
            // 'roles' => $user->getRoles(),
            //  'favorites' => array_map(fn($p) => $p->getName(), $user->getPerfumes()->toArray())
        ]);
    }
}
