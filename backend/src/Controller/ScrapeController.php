<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeController extends AbstractController
{
    #[Route('/api/scrape-books', name: 'scrape_books')]
    public function scrapeBooks(): JsonResponse
    {
        $url = 'http://books.toscrape.com/catalogue/category/books/travel_2/index.html';

        $client = new Client();
        $response = $client->request('GET', $url);
        $html = $response->getBody()->getContents();

        $crawler = new Crawler($html);

        $books = [];

        $crawler->filter('.product_pod')->each(function (Crawler $node) use (&$books) {
            $title = $node->filter('h3 a')->attr('title');
            $price = $node->filter('.price_color')->text();
            $img = $node->filter('img')->attr('src');

            $img = $node->filter('img')->attr('src');
            $imgUrl = 'http://books.toscrape.com/' . ltrim($img, './');

            $books[] = [
                'title' => $title,
                'price' => $price,
                'img'   => $imgUrl,
            ];
        });

        return new JsonResponse($books);
    }
}
