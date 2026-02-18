<?php

namespace App\Command;

use App\Service\PerfumeriasScraper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'app:scrape-perfumerias',
    description: 'Scrapea perfumes y los guarda en la base de datos'
)]
class ScrapePerfumeriasCommand extends Command
{
    private PerfumeriasScraper $scraper;

    public function __construct(PerfumeriasScraper $scraper)
    {
        parent::__construct();
        $this->scraper = $scraper;
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'url',
                'u',
                InputOption::VALUE_OPTIONAL,
                'URL de PCComponentes a scrapear',
                'https://perfumerias.com/perfumes-hombre/'
            )
            ->addOption(
                'category',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Categoría del producto',
                'Perfumes-hombre'
            );
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
       $url = $input->getOption('url');
        $category = $input->getOption('category');
        
        $output->writeln('⏳ Iniciando scraping de PCComponentes...');
        $output->writeln("   URL: $url");
        $output->writeln("   Categoría: $category");
        
        try {
            $this->scraper->scrape($url, $category);
            $output->writeln('✅ Componentes guardados correctamente');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>❌ Error: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
