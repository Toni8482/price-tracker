<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Perfumes;
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

    // asignar rol por defecto
    $newUser->setRoles(['ROLE_USER']);

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
}
