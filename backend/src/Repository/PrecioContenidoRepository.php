<?php

namespace App\Repository;

use App\Entity\PrecioContenido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PrecioContenido>
 */
class PrecioContenidoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrecioContenido::class);
    }



 public function findByPerfumeId($idPerfume): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.perfume = :val')
            ->setParameter('val', $idPerfume)
            ->getQuery()
            ->getResult()
        ;
    }
    
//    /**
//     * @return PrecioContenido[] Returns an array of PrecioContenido objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PrecioContenido
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
