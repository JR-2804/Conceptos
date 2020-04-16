<?php

namespace AppBundle\DTO;

class CheckOutDTO
{
    private $firstName;
    private $lastName;
    private $address;
    private $email;
    private $phone;
    private $movil;
    private $products;
    private $memberNumber;
    private $type;
    private $prefacture;
    private $ignoreTransport;

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getMovil()
    {
        return $this->movil;
    }

    public function setMovil($movil)
    {
        $this->movil = $movil;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function setProducts($products)
    {
        $this->products = $products;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getMemberNumber()
    {
        return $this->memberNumber;
    }

    public function setMemberNumber($memberNumber)
    {
        $this->memberNumber = $memberNumber;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getPrefacture()
    {
        return $this->prefacture;
    }

    public function setPrefacture($prefacture)
    {
        $this->prefacture = $prefacture;
    }

    public function getIgnoreTransport()
    {
        return $this->ignoreTransport;
    }

    public function setIgnoreTransport($ignoreTransport)
    {
        $this->ignoreTransport = $ignoreTransport;
    }
}
