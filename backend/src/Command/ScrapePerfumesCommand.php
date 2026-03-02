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
                'URL de perfumerias hombre a scrapear',
                'https://perfumerias.com/perfumes-hombre/'
            )
            ->addOption(
                'url2',
                'u2',
                InputOption::VALUE_OPTIONAL,
                'URL de perfumerias mujer a scrapear',
                'https://perfumerias.com/perfumes-mujer/'
            )
            ->addOption(
                'url3',
                'u3',
                InputOption::VALUE_OPTIONAL,
                'URL de perfumesclub hombre a scrapear',
                'https://www.perfumesclub.com/es/perfume/hombre/fs/'
            )
            ->addOption(
                'url4',
                'u4',
                InputOption::VALUE_OPTIONAL,
                'URL de perfumesclub mujer a scrapear',
                'https://www.perfumesclub.com/es/perfume/mujer/fs/'
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
        $urle = $input->getOption('url3');
        $urlu = $input->getOption('url4');
        $category = $input->getOption('category');

        $output->writeln('⏳ Iniciando scraping de Perfumes...');
        $output->writeln("   URL: $urla");
        $output->writeln("   URL: $urlo");
        $output->writeln("   Categoría: $category");


        $perH = [
            'url' => $urla,
            'base_url' => 'https://perfumerias.com',
            'publico' => 'hombre',
            'selector' => "div.mini_contenedor_grupo > div > a"
        ];
        $perM = [
            'url' => $urlo,
            'base_url' => 'https://perfumerias.com',
            'publico' => 'mujer',
            'selector' => "div.mini_contenedor_grupo > div > a"
        ];
        $clubH = [
            'url' => $urle,
            'base_url' => 'https://www.perfumesclub.com',
            'publico' => 'hombre',
            'selector' => "a.imageProductDouble"
        ];
        $clubM = [
            'url' => $urlu,
            'base_url' => 'https://www.perfumesclub.com',
            'publico' => 'mujer',
            'selector' => "a.imageProductDouble"
        ];


#$perH, $perM, $clubH,  $clubM
        $webSite = [$perH];
        try {

            $start = microtime(true);





            $this->scraper->scrape($webSite, $category);

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
