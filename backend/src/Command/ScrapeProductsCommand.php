<?php

namespace App\Command;

use App\Service\ProductScraper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:scrape-products',   // <- aquí va el nombre!
    description: 'Scrapea libros y los guarda en la base de datos'
)]
class ScrapeProductsCommand extends Command
{
    private ProductScraper $scraper;

    public function __construct(ProductScraper $scraper)
    {
        parent::__construct();
        $this->scraper = $scraper;
    }

   protected function execute(InputInterface $input, OutputInterface $output): int
{
    $output->writeln('⏳ Iniciando scraping de productos...');
    $this->scraper->scrape();
    $output->writeln('✅ Productos guardados correctamente');
    return Command::SUCCESS;
}
}