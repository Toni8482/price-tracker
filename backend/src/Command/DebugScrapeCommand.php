<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:debug-scrape',
    description: 'Debug: obtiene el HTML de PCComponentes y muestra estructura'
)]
class DebugScrapeCommand extends Command
{
    public function __construct(
        private HttpClientInterface $client
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $url = 'https://www.pccomponentes.com/procesadores';
        
        try {
            $response = $this->client->request('POST', 'http://scraper-service:3000/scrape', [
                'json' => ['url' => $url]
            ]);

            $data = $response->toArray();

            if (isset($data['html'])) {
                $html = $data['html'];
                
                // Guardar a archivo para inspeccionar
                file_put_contents('/tmp/pccomponentes.html', $html);
                $output->writeln('✅ HTML guardado en /tmp/pccomponentes.html');
                
                // Análisis rápido
                $output->writeln("\n📊 Análisis del HTML:");
                $output->writeln('- Tamaño: ' . strlen($html) . ' bytes');
                $output->writeln('- Tiene "producto": ' . (strpos($html, 'producto') !== false ? 'SÍ' : 'NO'));
                $output->writeln('- Tiene "product": ' . (strpos($html, 'product') !== false ? 'SÍ' : 'NO'));
                $output->writeln('- Tiene "price": ' . (strpos($html, 'price') !== false ? 'SÍ' : 'NO'));
                $output->writeln('- Tiene "precio": ' . (strpos($html, 'precio') !== false ? 'SÍ' : 'NO'));
                
                // Buscar todas las clases y IDs
                preg_match_all('/class=["\']([^"\']+)["\']/', $html, $classes);
                preg_match_all('/id=["\']([^"\']+)["\']/', $html, $ids);
                
                if (!empty($classes[1])) {
                    $unique_classes = array_unique($classes[1]);
                    $output->writeln("\n🏷️  Clases CSS encontradas (primeras 20):");
                    foreach (array_slice(array_unique(explode(' ', implode(' ', $unique_classes))), 0, 20) as $class) {
                        if (!empty(trim($class))) {
                            $output->writeln("  - $class");
                        }
                    }
                }
                
                return Command::SUCCESS;
            } else {
                $output->writeln('❌ No se obtuvo HTML');
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $output->writeln('❌ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
