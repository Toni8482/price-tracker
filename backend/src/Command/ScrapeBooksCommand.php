<?php

namespace App\Command;

use App\Service\BookScraper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:scrape-books',
    description: 'Scrapea libros y los guarda en la base de datos'
)]
class ScrapeBooksCommand extends Command
{
    public function __construct(
        private BookScraper $scraper
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->scraper->scrapeAndSave();

        $output->writeln('Libros scrapeados y guardados correctamente.');

        return Command::SUCCESS;
    }
}
