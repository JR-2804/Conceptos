<?php

namespace AppBundle\DTO;

use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

class CheckOutDTO
{
    private $name;
    private $address;
    private $email;
    private $phone;
    private $movil;
    private $products;
    private $memberNumber;
    private $type;
    private $prefacture;
    private $ignoreTransport;

    /**
     * @Recaptcha\IsTrue
     */
    public $recaptcha;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getMovil()
    {
        return $this->movil;
    }

    /**
     * @param mixed $movil
     */
    public function setMovil($movil)
    {
        $this->movil = $movil;
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param mixed $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getRecaptcha()
    {
        return $this->recaptcha;
    }

    /**
     * @param mixed $recaptcha
     */
    public function setRecaptcha($recaptcha)
    {
        $this->recaptcha = $recaptcha;
    }

    /**
     * @return mixed
     */
    public function getMemberNumber()
    {
        return $this->memberNumber;
    }

    /**
     * @param mixed $memberNumber
     */
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
