<?php

use Domain\Entities\ClientEntity;

class ClientEntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Domain\Entities\ClientEntity::setId
     * @covers \Domain\Entities\ClientEntity::setFirstName
     * @covers \Domain\Entities\ClientEntity::setLastName
     * @covers \Domain\Entities\ClientEntity::setEmail
     * @covers \Domain\Entities\ClientEntity::setAge
     * @covers \Domain\Entities\ClientEntity::toArray
     */
    public function testSetData()
    {
        $client = new ClientEntity();

        $expected = [
            'id'              => '1234',
            'first_name' => 'Silex',
            'last_name'  => 'Project',
            'email'         => 'lcfumes@gmail.com',
            'age'           => '1'
        ];

        $client->setId("1234");
        $client->setFirstName("Silex");
        $client->setLastName("Project");
        $client->setEmail("lcfumes@gmail.com");
        $client->setAge('1');

        $this->assertEquals($expected, $client->toArray());
    }

    /**
     * @covers \Domain\Entities\ClientEntity::getId
     * @covers \Domain\Entities\ClientEntity::getFirstName
     * @covers \Domain\Entities\ClientEntity::getLastName
     * @covers \Domain\Entities\ClientEntity::getEmail
     * @covers \Domain\Entities\ClientEntity::getAge
     * @covers \Domain\Entities\ClientEntity::toArray
     */
    public function testGetData()
    {
        $client = new ClientEntity();

        $client->setId("1234");
        $client->setFirstName("Silex");
        $client->setLastName("Project");
        $client->setEmail("lcfumes@gmail.com");
        $client->setAge('1');

        $expected = [
            'id'              => $client->getId(),
            'first_name' => $client->getFirstName(),
            'last_name'  => $client->getLastName(),
            'email'         => $client->getEmail(),
            'age'           => $client->getAge()
        ];

        $this->assertEquals($expected, $client->toArray());
    }
}