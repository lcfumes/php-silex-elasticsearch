<?php

namespace Domain\Repositories;

use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\ServerErrorResponseException;

class ElasticSearchClientRepository extends AbstractElasticSearchRepository
{
    protected $index = 'clients';

    protected $type = 'client';

    protected $config;

    public function __construct($config)
    {
        if (!is_array($config) || count($config) === 0) {
            throw new \InvalidArgumentException('Invalid Config to Repository');
        }

        $this->config = $config;

        $connect = false;
        foreach ($config['elasticsearch-server'] as $host) {
            if ($connect instanceof \Elasticsearch\Client) {
                continue;
            }
            $hosts = [
                $host['host'].':'.$host['port'],
            ];
            $connect = ClientBuilder::create()->setHosts($hosts)->build();
            if (!$connect->transport->getConnection()->ping()) {
                $connect = false;
            }
        }

        if (!$connect) {
            throw new ServerErrorResponseException('Server not responding');
        }
        parent::__construct($this->index, $connect);
    }

    public function setIndex($index) {
        if ($index === "") {
            throw new \InvalidArgumentException("Index undefined");
        }
        $this->index = $index;
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
                    'number_of_shards' => $this->config['elasticsearch-data']['shards'],
                    'number_of_replicas' => $this->config['elasticsearch-data']['replica'],
                    'analysis' => [
                        'filter' => [
                            'ngram' => [
                                'type' => 'ngram',
                                'min_gram' => 3,
                                'max_gram' => 3,
                            ],
                            'brazilian_stop' => [
                              'type' => 'stop',
                              'stopwords' => '_brazilian_',
                            ],
                            'brazilian_keywords' => [
                              'type' => 'keyword_marker',
                              'keywords' => ['de', 'a', 'e', 'da'],
                            ],
                            'brazilian_stemmer' => [
                              'type' => 'stemmer',
                              'language' => 'brazilian',
                            ],
                        ],
                        'analyzer' => [
                            'lcfumes' => [
                                'tokenizer' => 'standard',
                                'filter' => [
                                    'lowercase',
                                    'brazilian_stop',
                                    'brazilian_keywords',
                                    'brazilian_stemmer',
                                    'ngram',
                                ],
                            ],
                        ],
                    ],
                ],
                'mappings' => [
                    'client' => [
                        'properties' => [
                            'first_name' => [
                                'type' => 'string',
                                'analyzer' => 'lcfumes',
                            ],
                            'last_name' => [
                                'type' => 'string',
                                'analyzer' => 'lcfumes',
                            ],
                            'email' => [
                                'type' => 'string',
                                'analyzer' => 'lcfumes',
                            ],
                            'age' => [
                                'type' => 'integer',
                            ],
                        ],
                    ],
                ],
            ],
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
                'age' => (int) $client->getAge(),
            ],
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
                        'must' => $search,
                    ],
                ],
            ],
        ];

        return $this->client->search($params);
    }
}
