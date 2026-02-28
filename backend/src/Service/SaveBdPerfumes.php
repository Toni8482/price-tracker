<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Perfumes;
use App\Repository\StoresRepository;
use App\Repository\TargetPublicRepository;

class SaveBdPerfumes
{
    public function __construct(private EntityManagerInterface $em, private StoresRepository $storesRepository, private TargetPublicRepository $targetPublicRepository) {}

    public function savePerfumes(array $perfumes, string $base_url, string $publico)
    {
        foreach ($perfumes as $perfume) {

            $newPerfume = new Perfumes();
            $newPerfume->setBrand($perfume['marca']);
            $newPerfume->setName($perfume['nombre']);
            $newPerfume->setPrice($perfume['precio']);
            $newPerfume->setCategory("Hombre");
            $newPerfume->setImageUrl($perfume['url_imagen']);
            $newPerfume->setPerfumeUrl($perfume['url_producto']);
            $newPerfume->setDescription($perfume['descripcion']);
            $newPerfume->setStock(0);
            $newPerfume->setContenido($perfume['contenido']);
            $newPerfume->setConcentracion($perfume['concentracion']);
          

            $store = "";
            if ($base_url == "https://perfumerias.com") {

                $store = $this->storesRepository->find(1);
            } elseif ($base_url == "https://www.perfumesclub.com") {

                $store = $this->storesRepository->find(2);
            }
            $newPerfume->setStore($store);



            if ($publico == "hombre") {

                $publicoObjetivo = $this->targetPublicRepository->find(2);
            } elseif ($publico == "mujer") {

                $publicoObjetivo = $this->targetPublicRepository->find(1);
            }


            $newPerfume->setTargetPublic($publicoObjetivo);


            $this->em->persist($newPerfume);
        }

        $this->em->flush();
        $this->em->clear(); // elimina entidades persistidas de la memoria de Doctrine

    }
}
