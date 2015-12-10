<?php

namespace Domain\Services;

use Domain\Collectors\ClientCollector;
use Domain\Entities\ClientEntity;
use Domain\Repositories\ElasticSearchClientRepository;

class ElasticSearchClientService
{
    private $repository;

    public function __construct($repository)
    {
        if (!$repository instanceof ElasticSearchClientRepository) {
            throw new \InvalidArgumentException('Expected ElasticSearchClientRepository in ElasticsearchClient');
        }

        $this->repository = $repository;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function checkIndex()
    {
        return $this->repository->issetIndex();
    }

    public function createIndex()
    {
        return $this->repository->createIndex();
    }

    public function deleteIndex()
    {
        return $this->repository->deleteIndex();
    }

    public function saveClient($client)
    {
        if (!$client instanceof ClientEntity) {
            throw new \InvalidArgumentException('Expected ClientEntity in saveClient');
        }

        return $this->repository->saveClient($client);
    }

    /**
     * @var \Domain\Entities\ClientEntity
     *
     * @return \Domain\Collectors\ClientCollector
     */
    public function searchClient($client)
    {
        if (!$client instanceof ClientEntity) {
            throw new \InvalidArgumentException('Expected ClientEntity in saveClient');
        }

        $result = $this->repository->searchClient($client);

        $clientCollector = new ClientCollector();

        if ($result['hits']['total'] === 0) {
            return $clientCollector;
        }

        foreach ($result['hits']['hits'] as $client) {
            $clientEntity = new ClientEntity();

            $clientEntity->setId($client['_id']);
            $clientEntity->setFirstName($client['_source']['first_name']);
            $clientEntity->setLastName($client['_source']['last_name']);
            $clientEntity->setEmail($client['_source']['email']);
            $clientEntity->setAge($client['_source']['age']);

            $clientCollector->add($clientEntity);
        }

        return $clientCollector;
    }
}
