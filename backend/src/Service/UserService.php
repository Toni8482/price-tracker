<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Perfumes;
use App\Entity\PrecioContenido;
use App\Repository\UserRepository;
use App\Repository\PerfumesRepository;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private PerfumesRepository $PerfumesRepository,
        private UserPasswordHasherInterface $passwordHasher,

    ) {}

    public function saveUser(array $user)
    {
        $newUser = new User();

        $newUser->setEmail($user['email']);

        $totalUsers = $this->userRepository->count([]);

        if ($totalUsers === 0) {
            $newUser->setRoles(['ROLE_ADMIN']);
        } else {
              $newUser->setRoles(['ROLE_USER']);
        }


        $hashedPassword = $this->passwordHasher->hashPassword(
            $newUser,
            $user['password']
        );

        $newUser->setPassword($hashedPassword);

        $this->em->persist($newUser);
        $this->em->flush();
    }

    public function editUser(int $id, array $data): void
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new \Exception('User not found');
        }

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        if (!empty($data['password'])) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $data['password']
            );

            $user->setPassword($hashedPassword);
        }

        if (isset($data['roles'])) {
            $user->setRoles($data['roles']);
        }

        $this->em->flush();
    }

    public function deleteUser(int $id): void
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new \Exception('User not found');
        }

        $this->em->remove($user);
        $this->em->flush();
    }

    public function saveFavorites(User $user, Perfumes $perfume)
    {


        $user->addPerfume($perfume);

        $this->em->flush();
    }

    public function deleteFavorites(User $user, Perfumes $perfume)
    {


        $user->deletePerfume($perfume);

        $this->em->flush();
    }


    public function saveFavoritesVariable(User $user, PrecioContenido $perfume)
    {


        $user->addPerfumeVariable($perfume);

        $this->em->flush();
    }

    public function deleteFavoritesVariable(User $user, PrecioContenido $perfume)
    {


        $user->deletePerfumeVariable($perfume);

        $this->em->flush();
    }
}
