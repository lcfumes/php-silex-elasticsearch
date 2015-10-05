<?php

namespace Domain\Repositories;

use \Elasticsearch\Client;

abstract class ElasticSearchRepository
{

    protected $index;

    protected $client;

    public function __construct($index, Client $client)
    {
        if ($index == "") {
            throw new Exception('ElasticSearch Index not declared');
        }

        $this->index = $index;

        $this->client = $client;
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