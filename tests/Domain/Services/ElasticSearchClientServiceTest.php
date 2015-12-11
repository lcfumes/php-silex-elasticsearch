<?php

use Domain\Entities\ClientEntity;
use Domain\Collectors\ClientCollector;
use Domain\Services\ElasticSearchClientService;

class ElasticSearchClientServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessage  Expected ElasticSearchClientRepository in ElasticsearchClient
     * @cover \Domain\Services\ElasticSearchClientService::__construct
     */
    public function testConstructObjectWithoutRepository()
    {
            $repository = "Repository";
            new ElasticSearchClientService($repository);
    }

    /**
     * @cover \Domain\Services\ElasticSearchClientService::__construct
     * @cover \Domain\Services\ElasticSearchClientService::getRepository
     */
    public function testConstructObjectWIthRepository()
    {
        $mockRepository = $this->getMockBuilder('Domain\Repositories\ElasticSearchClientRepository')
                                             ->disableOriginalConstructor()
                                             ->getMock();
        $service = new ElasticSearchClientService($mockRepository);

        $this->assertInstanceOf('Domain\Repositories\ElasticSearchClientRepository', $service->getRepository());
    }

    /**
     * @cover \Domain\Services\ElasticSearchClientService::__construct
     * @cover \Domain\Services\ElasticSearchClientService::checkIndex
     */
    public function testCheckIndex()
    {
        $mockRepository = $this->getMockBuilder('Domain\Repositories\ElasticSearchClientRepository')
                                             ->disableOriginalConstructor()
                                             ->setMethods(['issetIndex'])
                                             ->getMock();
        $mockRepository->expects($this->once())
                                 ->method('issetIndex')
                                 ->will($this->returnValue(true));

        $service = new ElasticSearchClientService($mockRepository);

        $this->assertTrue($service->checkIndex());
    }

    /**
     * @cover \Domain\Services\ElasticSearchClientService::__construct
     * @cover \Domain\Services\ElasticSearchClientService::createIndex
     */
    public function testCreateIndex()
    {
        $mockRepository = $this->getMockBuilder('Domain\Repositories\ElasticSearchClientRepository')
                                             ->disableOriginalConstructor()
                                             ->setMethods(['createIndex'])
                                             ->getMock();
        $mockRepository->expects($this->once())
                                 ->method('createIndex')
                                 ->will($this->returnValue(true));

        $service = new ElasticSearchClientService($mockRepository);

        $this->assertTrue($service->createIndex());
    }

    /**
     * @cover \Domain\Services\ElasticSearchClientService::__construct
     * @cover \Domain\Services\ElasticSearchClientService::deleteIndex
     */
    public function testDeleteIndex()
    {
        $mockRepository = $this->getMockBuilder('Domain\Repositories\ElasticSearchClientRepository')
                                             ->disableOriginalConstructor()
                                             ->setMethods(['deleteIndex'])
                                             ->getMock();
        $mockRepository->expects($this->once())
                                 ->method('deleteIndex')
                                 ->will($this->returnValue(true));

        $service = new ElasticSearchClientService($mockRepository);

        $this->assertTrue($service->deleteIndex());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessage  Expected ClientEntity in saveClient
     * @cover \Domain\Services\ElasticSearchClientService::__construct
     * @cover \Domain\Services\ElasticSearchClientService::saveClient
     */
    public function testSaveClientWhenParameterIsntInstanceOfClient()
    {
        $mockRepository = $this->getMockBuilder('Domain\Repositories\ElasticSearchClientRepository')
                                             ->disableOriginalConstructor()
                                            ->getMock();
        $service = new ElasticSearchClientService($mockRepository);

        $service->saveClient('Luiz Fumes');
    }

     /**
     * @cover \Domain\Services\ElasticSearchClientService::__construct
     * @cover \Domain\Services\ElasticSearchClientService::saveClient
     */
    public function testSaveIndexWhemParameterIsInstanceOfClient()
    {
        $mockRepository = $this->getMockBuilder('Domain\Repositories\ElasticSearchClientRepository')
                                             ->disableOriginalConstructor()
                                             ->setMethods(['saveClient'])
                                             ->getMock();
        $mockRepository->expects($this->once())
                                 ->method('saveClient')
                                 ->will($this->returnValue(true));

        $service = new ElasticSearchClientService($mockRepository);

        $client = new ClientEntity();

        $client->setId("1234");
        $client->setFirstName("Silex");
        $client->setLastName("Project");
        $client->setEmail("lcfumes@gmail.com");
        $client->setAge('1');

        $this->assertTrue($service->saveClient($client));
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessage  Expected ClientEntity in saveClient
     * @cover \Domain\Services\ElasticSearchClientService::__construct
     * @cover \Domain\Services\ElasticSearchClientService::searchClient
     */
    public function testSearchClientWhenParameterIsntInstanceOfClientEntity()
    {
        $mockRepository = $this->getMockBuilder('Domain\Repositories\ElasticSearchClientRepository')
                                             ->disableOriginalConstructor()
                                             ->setMethods(['searchClient'])
                                             ->getMock();

        $service = new ElasticSearchClientService($mockRepository);

        $service->searchClient("Luiz Fumes");
    }

    /**
     * @cover \Domain\Services\ElasticSearchClientService::__construct
     * @cover \Domain\Services\ElasticSearchClientService::searchClient
     */
    public function testSearchClientWhenParameterIsInstanceOfClientEntity()
    {
        $returnSearch = [
            'hits' => [
                'total' => 1,
                'hits' => [
                    '0' => [
                        '_index' => 'clients',
                        '_type' => 'client',
                        '_id' => 'AVGNpJecBuYDQw4mIXXb',
                        '_source' => [
                            'first_name' => 'Luiz',
                            'last_name' => 'Fumes',
                            'email' => 'lcfumes@gmail.com',
                            'age' => '33',
                        ]
                    ]
                ]
            ]
        ];

        $mockRepository = $this->getMockBuilder('Domain\Repositories\ElasticSearchClientRepository')
                                             ->disableOriginalConstructor()
                                             ->setMethods(['searchClient'])
                                             ->getMock();
        $mockRepository->expects($this->once())
                                 ->method('searchClient')
                                 ->will($this->returnValue($returnSearch));

        $service = new ElasticSearchClientService($mockRepository);

        $client = new ClientEntity();

        $client->setFirstName("Luiz");

        $this->assertInstanceOf('\Domain\Collectors\ClientCollector', $service->searchClient($client));
    }

    /**
     * @cover \Domain\Services\ElasticSearchClientService::__construct
     * @cover \Domain\Services\ElasticSearchClientService::searchClient
     */
    public function testSearchClientWhenDontFindClient()
    {
        $returnSearch = [
            'hits' => [
                'total' => 0,
            ]
        ];

        $mockRepository = $this->getMockBuilder('Domain\Repositories\ElasticSearchClientRepository')
                                             ->disableOriginalConstructor()
                                             ->setMethods(['searchClient'])
                                             ->getMock();
        $mockRepository->expects($this->once())
                                 ->method('searchClient')
                                 ->will($this->returnValue($returnSearch));

        $service = new ElasticSearchClientService($mockRepository);

        $client = new ClientEntity();

        $client->setFirstName("Luiz");

        $this->assertInstanceOf('\Domain\Collectors\ClientCollector', $service->searchClient($client));
    }

}