<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiBookController extends AbstractController
{
    #[Route('/api/books', name: 'api_books', methods: ['GET'])]
    public function index(BookRepository $bookRepository): JsonResponse
    {
        $books = $bookRepository->findAll();

        $data = [];

        foreach ($books as $book) {
            $data[] = [
                'id'    => $book->getId(),
                'title' => $book->getTitle(),
                'price' => $book->getPrice(),
                'image' => $book->getImageUrl(),
            ];
        }

        return new JsonResponse($data);
    }

      #[Route('/api/books/{id}', name: 'api_book_detail', methods: ['GET'])]
    public function show(BookRepository $bookRepository, int $id): JsonResponse
    {
        $book = $bookRepository->find($id);

        if (!$book) {
            return new JsonResponse(['error' => 'Libro no encontrado'], 404);
        }

        $data = [
            'id'    => $book->getId(),
            'title' => $book->getTitle(),
            'price' => $book->getPrice(),
            'image' => $book->getImageUrl(),
        ];

        return new JsonResponse($data);
    }
}
