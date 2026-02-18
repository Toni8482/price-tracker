<?php

namespace App\Service;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class BookScraper
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function scrapeAndSave(): void
    {
        $url = 'http://books.toscrape.com/catalogue/category/books/travel_2/index.html';

        $client = new Client();
        $html = $client->request('GET', $url)->getBody()->getContents();

        $crawler = new Crawler($html);

        $crawler->filter('.product_pod')->each(function (Crawler $node) {
            $title = $node->filter('h3 a')->attr('title');

            $priceText = $node->filter('.price_color')->text();
            $price = (float) str_replace(['£', 'Â'], '', $priceText);

            $img = $node->filter('img')->attr('src');
            $imgUrl = 'http://books.toscrape.com/' . ltrim($img, './');

            // evitar duplicados
            $existing = $this->em
                ->getRepository(Book::class)
                ->findOneBy(['title' => $title]);

            if ($existing) {
                return;
            }

            $book = new Book();
            $book->setTitle($title);
            $book->setPrice($price);
            $book->setImageUrl($imgUrl);

            $this->em->persist($book);
        });

        $this->em->flush();
    }
}
