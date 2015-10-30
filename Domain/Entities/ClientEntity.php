<?php

namespace Domain\Entities;

class ClientEntity
{
    private $id;

    private $firstName;

    private $lastName;

    private $email;

    private $age;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setAge($age)
    {
        $this->age = $age;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function toArray() {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email'     => $this->email,
            'age'       => $this->age,
        ];
    }

}
