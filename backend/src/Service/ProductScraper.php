<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductScraper
{
    private string $baseUrl = 'http://books.toscrape.com/catalogue/';

    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $em
    ) {}

    public function scrape(): void
    {
        $contadorPaginas = 1;
        while ($contadorPaginas <= 50) {



            $response = $this->client->request('GET', $this->baseUrl . 'page-' . $contadorPaginas . '.html');
            $html = $response->getContent();

            libxml_use_internal_errors(true);
            $dom = new \DOMDocument();
            $dom->loadHTML($html);
            $xpath = new \DOMXPath($dom);

            $products = $xpath->query("//article[contains(@class,'product_pod')]");
            /** @var \DOMElement $item */
            foreach ($products as $item) {
                $hrefNode = $item->getElementsByTagName('h3')->item(0)?->getElementsByTagName('a')->item(0);
                if (!$hrefNode) continue;

                $productUrl = $this->baseUrl . ltrim($hrefNode->getAttribute('href'), './');

                $this->scrapeProductDetail($productUrl);
            }

            $this->em->flush();

              $contadorPaginas++;
        }
    }

    private function scrapeProductDetail(string $url): void
    {
        $response = $this->client->request('GET', $url);
        $html = $response->getContent();

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);

        // Título
        $titleNode = $xpath->query("//div[contains(@class,'product_main')]//h1");
        $title = $titleNode->length ? trim($titleNode->item(0)->textContent) : 'Sin título';

        // Precio
        $priceNode = $xpath->query("//p[@class='price_color']");
        $priceText = $priceNode->length ? $priceNode->item(0)->textContent : '£0';
        $price = (float) str_replace('£', '', $priceText);

        // Descripción
        $descNode = $xpath->query("//article[@class='product_page']/p");
        $description = $descNode->length ? trim($descNode->item(0)->textContent) : 'Sin descripción';

        // Género
        $genreNode = $xpath->query("//ul[@class='breadcrumb']/li[3]/a");
        $genre = $genreNode->length ? trim($genreNode->item(0)->textContent) : null;

        // Stock
        $stockNode = $xpath->query("//p[contains(@class,'instock')]");
        $stockText = $stockNode->length ? $stockNode->item(0)->textContent : '0';
        preg_match('/\d+/', $stockText, $matches);
        $stock = isset($matches[0]) ? (int) $matches[0] : 0;

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    // Rating
        /** @var \DOMList $ratingNode */
        $ratingNode = $xpath->query("//p[contains(@class,'star-rating')]");
        $rating = ($ratingNode->length && $ratingNode->item(0) instanceof \DOMElement)
            ? $this->parseRating($ratingNode->item(0)->getAttribute('class'))
            : 0;

                      $baseUrlImg = 'http://books.toscrape.com/';                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      // Imagen
        /** @var \DOMList $imgNode */
        $imgNode = $xpath->query("//div[@class='item active']/img");
        $imageUrl = $imgNode->length ? $baseUrlImg . ltrim($imgNode->item(0)->getAttribute('src'), './') : null;

        // Evitar duplicados
        $existing = $this->em->getRepository(Product::class)->findOneBy(['enlace_product' => $url]);
        if ($existing) {
            return; // ya existe
        }

        // Crear producto
        $product = new Product();
        $product
            ->setName($title)
            ->setPrice($price)
            ->setDescription($description)
            ->setGenero($genre)
            ->setStock($stock)
            ->setRating($rating)
            ->setUrl($imageUrl)
            ->setEnlaceProduct($url)
            ->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($product);
    }

    private function parseRating(string $class): int
    {
        return match (true) {
            str_contains($class, 'One') => 1,
            str_contains($class, 'Two') => 2,
            str_contains($class, 'Three') => 3,
            str_contains($class, 'Four') => 4,
            str_contains($class, 'Five') => 5,
            default => 0,
        };
    }
}
