<?php

namespace AppBundle\DTO;


use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

class EmailDTO
{
    private $name;
    private $lastName;
    private $email;
    private $path;
    private $phone;
    private $text;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getRecaptcha()
    {
        return $this->recaptcha;
    }

    public function setRecaptcha($recaptcha)
    {
        $this->recaptcha = $recaptcha;
    }
}
