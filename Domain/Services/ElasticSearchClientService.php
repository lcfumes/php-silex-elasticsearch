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
                    'number_of_replicas' => 0
                ],
                'mappings' => [
                    '_default_' => [
                        '_source' => [
                            'enabled' => true,
                            'properties' => [
                                'first_name' => [
                                    'type' => 'string',
                                    'analyzer' => 'standard'
                                ],
                                'last_name' => [
                                    'type' => 'string',
                                    'analyzer' => 'standard'
                                ],
                                'email' => [
                                    'type' => 'string',
                                    'analyzer' => 'standard'
                                ],
                                'age' => [
                                    'type' => 'integer'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $this->client->indices()->create($params);
    }

    public function addDocument(\Domain\Entities\ClientEntity $client)
    {

        $params = [
            'index' => $this->index,
            'type' => $this->index,
            'body' => [
                'first_name' => $client->getFirstName(),
                'last_name' => $client->getLastName(),
                'email' => $client->getEmail(),
                'age' => (int)$client->getAge(),
            ]
        ];

        return $this->client->index($params);
    }

}