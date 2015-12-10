<?php

namespace Domain\Services;

use Domain\Repositories\ElasticSearchClientRepository;

class ElasticSearchClientService
{
    private $repository;

    public function __construct(ElasticSearchClientRepository $repository)
    {
        $this->repository = $repository;
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

    public function saveClient(\Domain\Entities\ClientEntity $client)
    {
        return $this->repository->saveClient($client);
    }

    /**
     * @var \Domain\Entities\ClientEntity
     *
     * @return \Domain\Collectors\ClientCollector
     */
    public function searchClient(\Domain\Entities\ClientEntity $client)
    {
        $result = $this->repository->searchClient($client);

        $clientCollector = new \Domain\Collectors\ClientCollector();

        if ($result['hits']['total'] > 0) {
            foreach ($result['hits']['hits'] as $client) {
                $clientEntity = new \Domain\Entities\ClientEntity();

                $clientEntity->setId($client['_id']);
                $clientEntity->setFirstName($client['_source']['first_name']);
                $clientEntity->setLastName($client['_source']['last_name']);
                $clientEntity->setEmail($client['_source']['email']);
                $clientEntity->setAge($client['_source']['age']);

                $clientCollector->add($clientEntity);
            }
        }

        return $clientCollector;
    }
}
