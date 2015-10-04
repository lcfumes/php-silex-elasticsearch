<?php

namespace Domain\Services;

use \Elasticsearch\ClientBuilder;

class ElasticSearchClientService extends ElasticSearchService
{
    protected $index = 'clients';

    public function __construct()
    {
        parent::__construct($this->index, ClientBuilder::create()->build());
    }

    public function issetIndex()
    {
        $params = [
            'index' => $this->index,
        ];
        return $this->client->indices()->exists($params);

    }

    public function createIndex()
    {
        $params = [
            'index' => $this->index,
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 1
                ]
            ]
        ];

        return $this->client->indices()->create($params);
    }

}