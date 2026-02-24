<?php

namespace App\Command;


use App\Service\PerfumesScraperService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'app:scrape-perfumes',
    description: 'Scrapea perfumes y los guarda en la base de datos'
)]
class ScrapePerfumesCommand extends Command
{
    private PerfumesScraperService $scraper;

    public function __construct(PerfumesScraperService $scraper)
    {
        parent::__construct();
        $this->scraper = $scraper;
    }

    protected function configure(): void
    {
        $this

            ->addOption(
                'url1',
                'u1',
                InputOption::VALUE_OPTIONAL,
                'URL de perfumerias a scrapear',
                'https://perfumerias.com/perfumes-hombre/'
            )
            ->addOption(
                'url2',
                'u2',
                InputOption::VALUE_OPTIONAL,
                'URL de perfumesclub a scrapear',
                'https://www.perfumesclub.com/es/perfume/hombre/fs/'
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
        $urla = $input->getOption('url1');
        $urlo = $input->getOption('url2');
        $category = $input->getOption('category');

        $output->writeln('⏳ Iniciando scraping de Perfumes...');
        $output->writeln("   URL: $urla");
        $output->writeln("   URL: $urlo");
        $output->writeln("   Categoría: $category");
        $urls =[$urla, $urlo];
        try {

            $start = microtime(true);





            $this->scraper->scrape($urls, $category);
            $output->writeln('✅ Componentes guardados correctamente');


            $end = microtime(true);

            $time = $end - $start;


            $output->writeln('⏱️ Tiempo total: ' . round($time, 2) . ' segundos');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>❌ Error: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
