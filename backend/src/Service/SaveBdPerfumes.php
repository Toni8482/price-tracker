<?php

namespace App\Service;



use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Perfumes;



class SaveBdPerfumes
{



 public function __construct(private EntityManagerInterface $em)
    {
      
    }





    public function savePerfumes(array $perfumes)
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
            $newPerfume->setStore($perfume['tienda']);
            $newPerfume->setContenido($perfume['contenido']);
            $newPerfume->setConcentracion($perfume['concentracion']);


            $this->em->persist($newPerfume);
        }


        $this->em->flush();
        $this->em->clear(); // elimina entidades persistidas de la memoria de Doctrine




    }





}