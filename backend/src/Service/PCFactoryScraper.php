<?php

namespace App\Service;

use App\Entity\PCFactoryComponent;
use App\Repository\PCFactoryComponentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PCFactoryScraper
{
    private string $scraperUrl = 'http://scraper-service:3000';
    private array $processed_urls = [];

    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $em,
        private PCFactoryComponentRepository $componentRepository
    ) {}

    public function scrape(string $url, string $category): void
    {
        try {
            // Intentar con selectores específicos
            $selectors = ['.rr-item', '.card', '[data-product]', '.product'];

            foreach ($selectors as $selector) {
                $response = $this->client->request('POST', $this->scraperUrl . '/scrape', [
                    'json' => ['url' => $url, 'selector' => $selector]
                ]);

                $data = $response->toArray();
                if (isset($data['data']) && is_array($data['data']) && count($data['data']) > 0) {
                    // Si tenemos datos, parsearlos
                    foreach ($data['data'] as $item) {
                        $this->processItem($item, $url, $category);
                    }
                    $this->em->flush();
                    return;
                }
            }

            // Fallback: obtener HTML completo y parsear
            $response = $this->client->request('POST', $this->scraperUrl . '/scrape', [
                'json' => ['url' => $url, 'selector' => 'body']
            ]);

            $data = $response->toArray();
            if (isset($data['html'])) {
                $this->parseFullHtml($data['html'], $url, $category);
                $this->em->flush();
            }
        } catch (\Exception $e) {
            throw new \RuntimeException('Error al scrapear PC Factory: ' . $e->getMessage());
        }
    }

    private function processItem($item, string $baseUrl, string $category): void
    {
        // Si el item es array, convertirlo a HTML string
        if (is_array($item)) {
            $itemHtml = $item['html'] ?? $item['content'] ?? null;
            if (!$itemHtml) {
                return;
            }
        } else {
            $itemHtml = (string)$item;
        }

        $this->extractFromHtml($itemHtml, $baseUrl, $category);
    }

    private function extractFromHtml(string $html, string $baseUrl, string $category): void
    {
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new \DOMXPath($dom);

        // Buscar nombre
        $nameNodes = $xpath->query("//*[contains(@class, 'rr-item__name')]");
        if ($nameNodes->length === 0) return;

        $nameElement = $nameNodes->item(0);
        if (!$nameElement) return;
        
        $name = trim($nameElement->textContent ?? '');
        if (empty($name)) return;

        // Buscar precio
        $price = 0.0;
        $priceQuery = $xpath->query("//*[contains(@class, 'rr-item__special-price') or contains(@class, 'rr-item__price')]");
        if ($priceQuery->length > 0) {
            $priceElement = $priceQuery->item(0);
            if ($priceElement) {
                $priceText = $priceElement->textContent ?? '';
                preg_match('/[\d,\.]+/', str_replace('.', '', $priceText), $matches);
                if (!empty($matches)) {
                    $price = (float) str_replace(',', '.', $matches[0]);
                }
            }
        }

        // Buscar URL
        $linkQuery = $xpath->query(".//a[@href]");
        $productUrl = null;
        if ($linkQuery->length > 0) {
            $linkElement = $linkQuery->item(0);
            if ($linkElement instanceof \DOMElement) {
                $href = $linkElement->getAttribute('href');
                
                // Normalizar URL
                if (strpos($href, 'http') === 0) {
                    // URL absoluta con protocolo
                    $productUrl = $href;
                } elseif (strpos($href, '//') === 0) {
                    // Protocol-relative URL
                    $productUrl = 'https:' . $href;
                } elseif (strpos($href, '/') === 0) {
                    // URL absoluta relativa al dominio
                    $baseHost = 'https://www.pcfactory.cl';
                    $productUrl = rtrim($baseHost, '/') . $href;
                } else {
                    // URL relativa
                    $productUrl = rtrim($baseUrl, '/') . '/' . ltrim($href, '/');
                }
            }
        }

        if (!$productUrl) return;

        // Evitar duplicados
        if (in_array($productUrl, $this->processed_urls) || 
            $this->componentRepository->findOneBy(['url' => $productUrl])) {
            return;
        }

        $this->processed_urls[] = $productUrl;

        $component = new PCFactoryComponent();
        $component->setName(substr($name, 0, 255));
        $component->setUrl($productUrl);
        $component->setCategory($category);
        $component->setPrice($price);
        $component->setDescription(substr($name, 0, 500));
        $component->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($component);
    }

    private function parseFullHtml(string $html, string $baseUrl, string $category): void
    {
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new \DOMXPath($dom);

        // Buscar todos los items con clase rr-item
        $items = $xpath->query("//*[contains(@class, 'rr-item')]");

        foreach ($items as $item) {
            if (!($item instanceof \DOMElement)) continue;

            // Buscar nombre dentro del item
            $nameQuery = $xpath->query(".//*[contains(@class, 'rr-item__name')]", $item);
            if ($nameQuery->length === 0) continue;

            $nameElement = $nameQuery->item(0);
            if (!$nameElement) continue;
            
            $name = trim($nameElement->textContent ?? '');
            if (empty($name)) continue;

            // Buscar precio
            $price = 0.0;
            $priceQuery = $xpath->query(".//*[contains(@class, 'rr-item__special-price') or contains(@class, 'rr-item__price')]", $item);
            if ($priceQuery->length > 0) {
                $priceElement = $priceQuery->item(0);
                if ($priceElement) {
                    $priceText = $priceElement->textContent ?? '';
                    preg_match('/[\d,\.]+/', str_replace('.', '', $priceText), $matches);
                    if (!empty($matches)) {
                        $price = (float) str_replace(',', '.', $matches[0]);
                    }
                }
            }

            // Buscar enlace
            $linkQuery = $xpath->query(".//a[@href]", $item);
            if ($linkQuery->length === 0) continue;

            $linkElement = $linkQuery->item(0);
            if (!($linkElement instanceof \DOMElement)) continue;

            $href = $linkElement->getAttribute('href');
            
            // Normalizar URL
            if (strpos($href, 'http') === 0) {
                // URL absoluta con protocolo
                $productUrl = $href;
            } elseif (strpos($href, '//') === 0) {
                // Protocol-relative URL
                $productUrl = 'https:' . $href;
            } elseif (strpos($href, '/') === 0) {
                // URL absoluta relativa al dominio
                $productUrl = 'https://www.pcfactory.cl' . $href;
            } else {
                // URL relativa
                $productUrl = rtrim($baseUrl, '/') . '/' . ltrim($href, '/');
            }

            // Evitar duplicados
            if (in_array($productUrl, $this->processed_urls) || 
                $this->componentRepository->findOneBy(['url' => $productUrl])) {
                continue;
            }

            $this->processed_urls[] = $productUrl;

            $component = new PCFactoryComponent();
            $component->setName(substr($name, 0, 255));
            $component->setUrl($productUrl);
            $component->setCategory($category);
            $component->setPrice($price);
            $component->setDescription(substr($name, 0, 500));
            $component->setCreatedAt(new \DateTimeImmutable());

            $this->em->persist($component);
        }
    }
}
