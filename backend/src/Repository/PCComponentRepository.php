<?php

namespace App\Repository;

use App\Entity\PCComponent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PCComponentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PCComponent::class);
    }

    public function findByUrl(string $url): ?PCComponent
    {
        return $this->findOneBy(['url' => $url]);
    }

    public function findByCategory(string $category)
    {
        return $this->findBy(['category' => $category]);
    }
}
