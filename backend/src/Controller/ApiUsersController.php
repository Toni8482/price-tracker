<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UserService;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;
use App\Repository\UserRepository;


final class ApiUsersController extends AbstractController
{

    /**
     * Guardar usuario nuevo
     */
    #[Route('/users', name: 'app_api_users', methods: ['POST'])]
    public function index(Request $request, UserService $userService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $userService->saveUser($data);

        return $this->json([
            'message' => 'Contenido del body',
            'user' =>  $data,
        ]);
    }

    /**
     * Devolver datos de usuario logueado
     */
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
            'roles' => $user->getRoles(),
            //  'favorites' => array_map(fn($p) => $p->getName(), $user->getPerfumes()->toArray())
        ]);
    }


    /**
     * Devuelve todos los usuarios
     */
    #[Route('/api/all/users', name: 'api_all_users', methods: ['GET'])]
    public function users(UserRepository $userRepository): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $users = $userRepository->findAll();
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles()
            ];
        }
        return $this->json($data);
    }

    /**
     * Editar usuario
     */
    #[Route('/api/user/{id}', name: 'api_edit_user', methods: ['PUT'])]
    public function editUser(
        int $id,
        Request $request,
        UserService $userService
    ): JsonResponse {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $data = json_decode($request->getContent(), true);

        $userService->editUser($id, $data);

        return $this->json([
            'message' => 'User updated'
        ]);
    }



    /**
     * Eliminar usuario
     */
    #[Route('/api/user/{id}', name: 'api_delete_user', methods: ['DELETE'])]
    public function deleteUser(
        int $id,
        UserService $userService,
        #[CurrentUser] ?User $currentUser
    ): JsonResponse {

        if (
            !$this->isGranted('ROLE_ADMIN')
            && $currentUser?->getId() !== $id
        ) {
            throw $this->createAccessDeniedException();
        }

        $userService->deleteUser($id);

        return $this->json([
            'message' => 'User deleted'
        ]);
    }
}
