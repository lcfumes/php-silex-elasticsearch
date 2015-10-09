<?php

namespace Commands\Clients;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

use Domain\Repositories\ElasticSearchClientRepository;
use Domain\Services\ElasticSearchClientService;

use app\Application;

class CreateIndexCommand extends Command
{
    protected function configure()
    {
        $this->setName("clients.createindex")
             ->setDescription("Criar Indice de Clientes no Elasticsearch e tipagem de campos")
             ->addArgument(
                 'action', InputArgument::REQUIRED, 'Do you want create or delete an index?'
              )
             ->setHelp("Create Index Clients");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = new Application();

        $elasticSearchClient = new ElasticSearchClientService(new ElasticSearchClientRepository($app['config']));

        $checkIndex = $elasticSearchClient->checkIndex();

        $action = $input->getArgument('action');

        if ($checkIndex && $action == 'create') {
            $header_style = new OutputFormatterStyle('white', 'red', array('bold'));
            $output->getFormatter()->setStyle('header', $header_style);

            $output->writeln('<header>Indice já existe</header>');
        }

        if (!$checkIndex && $action == 'create') {
            $elasticSearchClient->createIndex();
            $header_style = new OutputFormatterStyle('white', 'green', array('bold'));
            $output->getFormatter()->setStyle('header', $header_style);

            $output->writeln('<header>Indice criado</header>');

        }

    }
}
