<?php

namespace Domain\Services;

abstract class ElasticSearchService
{

    protected $index;

    protected $service;

    protected $client;

    public function __construct($index, \Elasticsearch\Client $client)
    {
        if ($index == "") {
            throw new Exception('ElasticSearch Index not declared');
        }

        $this->index = $index;

        $this->client = $client;
    }

    public function getService()
    {
        return $this->service;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function getClient()
    {
        return $this->client;
    }
}