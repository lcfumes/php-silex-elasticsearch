<?php

namespace Domain\Repositories;

use \Domain\Services\ElasticSearchClientRepository;

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

    public function addDocument(\Domain\Entities\ClientEntity $client)
    {
        return $this->repository->addDocument($client);
    }

}