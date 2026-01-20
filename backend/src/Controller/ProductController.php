<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/add-product', name: 'add_product')]
    public function addProduct(EntityManagerInterface $em): Response
    {
        // Crear un nuevo producto
        $product = new Product();
        $product->setName('Camiseta Symfony');
        $product->setPrice(19.99);
        $product->setUrl('https://example.com/camiseta-symfony');
        $product->setCreatedAt(new \DateTimeImmutable());

        // Persistir y guardar en la base de datos
        $em->persist($product);
        $em->flush();

        return new Response('Producto agregado con ID '.$product->getId());
    }
}
