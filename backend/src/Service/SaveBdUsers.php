<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\PerfumesRepository;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SaveBdUsers
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




        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $this->passwordHasher->hashPassword(
            $newUser,
            $user['password']
        );
        $newUser->setPassword($hashedPassword);


        $this->em->persist($newUser);
        $this->em->flush();
        $this->em->clear();
    }

    public function saveFavorites(array $favorites,)
    {
       


            $user = $this->userRepository->find($favorites['user_id']);
            $perfume = $this->PerfumesRepository->find($favorites['perfume_id']);
            $user->addPerfume($perfume);

            $this->em->flush();

    }
}
