<?php

namespace Domain\Repositories;

class ElasticSearchClientRepository
{

    public function __construct(\Domain\Services\ElasticSearchClientService $service)
    {
        $this->service = $service;
    }

    public function checkIndex()
    {
        return $this->service->issetIndex();
    }

    public function createIndex()
    {
        return $this->service->createIndex();
    }

}