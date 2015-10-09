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

    public function searchClient(\Domain\Entities\ClientEntity $client)
    {
        return $this->repository->searchClient($client);
    }

}
