<?php

namespace Commands\Clients;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

use \Domain\Repositories\ElasticSearchClientRepository;

class CreateIndexCommand extends Command
{
    protected function configure()
    {
        $start = 0;
        $stop = 100;

        $this->setName("clients.createindex")
             ->setDescription("Criar Indice de Clientes no Elasticsearch e tipagem de campos")
             ->setHelp("Create Index Clients");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $elasticSearchClient = new ElasticSearchClientRepository(new \Domain\Services\ElasticSearchClientService());

        if ($elasticSearchClient->checkIndex()) {
            $header_style = new OutputFormatterStyle('white', 'red', array('bold'));
            $output->getFormatter()->setStyle('header', $header_style);

            $output->writeln('<header>Indice já existe</header>');

            return;
        }

        $elasticSearchClient->createIndex();

        $header_style = new OutputFormatterStyle('white', 'green', array('bold'));
        $output->getFormatter()->setStyle('header', $header_style);

        $output->writeln('<header>Indice criado</header>');
    }
}
