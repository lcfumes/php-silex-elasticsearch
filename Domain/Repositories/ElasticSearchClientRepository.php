<?php

namespace Domain\Repositories;

use Elasticsearch\ClientBuilder;

class ElasticSearchClientRepository extends AbstractElasticSearchRepository
{
    protected $index = 'clients';

    protected $type = 'client';

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;

        $hosts = [
            $config['elasticsearch']['host'].':'.$config['elasticsearch']['port'],
        ];
        parent::__construct($this->index, ClientBuilder::create()->setHosts($hosts) ->build());
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
                    'number_of_shards' => $this->config['elasticsearch']['shards'],
                    'number_of_replicas' => $this->config['elasticsearch']['replica'],
                    'analysis' => [
                        "filter" => [
                            "ngram" => [
                                "type" => "ngram",
                                "min_gram" => 3,
                                "max_gram" => 3
                            ],
                            "brazilian_stop" => [
                              "type" => "stop",
                              "stopwords" => "_brazilian_" 
                            ],
                            "brazilian_keywords" => [
                              "type" => "keyword_marker",
                              "keywords" => ['de', 'a', 'e', 'da']
                            ],
                            "brazilian_stemmer" => [
                              "type" => "stemmer",
                              "language" => "brazilian"
                            ]
                        ],
                        "analyzer" => [
                            "lcfumes" => [
                                "tokenizer" =>  "standard",
                                "filter" => [
                                    "lowercase",
                                    "brazilian_stop",
                                    "brazilian_keywords",
                                    "brazilian_stemmer",
                                    "ngram"
                                ]
                            ]
                        ]
                    ],
                ],
                'mappings' => [
                    'client' => [
                        'properties' => [
                            'first_name' => [
                                'type' => 'string',
                                "analyzer" => "lcfumes"
                            ],
                            'last_name' => [
                                'type' => 'string',
                                "analyzer" => "lcfumes"
                            ],
                            'email' => [
                                'type' => 'string',
                                "analyzer" => "lcfumes"
                            ],
                            'age' => [
                                'type' => 'integer'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $this->client->indices()->create($params);
    }

    public function deleteIndex()
    {
        $params = [
            'index' => $this->index,
        ];

        return $this->client->indices()->delete($params);
    }

    public function saveClient(\Domain\Entities\ClientEntity $client)
    {

        if (!$this->issetIndex()) {
            $this->createIndex();
        }

        $params = [
            'index' => $this->index,
            'type' => $this->type,
            'body' => [
                'first_name' => $client->getFirstName(),
                'last_name' => $client->getLastName(),
                'email' => $client->getEmail(),
                'age' => (int)$client->getAge(),
            ]
        ];

        if (!is_null($client->getId())) {
            $params['id'] = $client->getId();
        }

        return $this->client->index($params);
    }

    public function searchClient(\Domain\Entities\ClientEntity $client)
    {

        if (!$this->issetIndex()) {
            return [];
        }

        $search = null;

        if (strlen($client->getFirstName()) > 0) {
            $search[]['match']['first_name'] = $client->getFirstName();
        }

        if (strlen($client->getLastName()) > 0) {
            $search[]['match']['last_name'] = $client->getLastName();
        }

        if (strlen($client->getEmail()) > 0) {
            $search[]['match']['email'] = $client->getEmail();
        }

        if (strlen($client->getAge()) > 0) {
            $search[]['match']['age'] = $client->getAge();
        }

        $params = [
            'index' => $this->index,
            'type' => $this->type,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => $search
                    ]
                ]
            ]
        ];

        return $this->client->search($params);
    }

}
 
