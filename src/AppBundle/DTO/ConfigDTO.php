<?php

namespace AppBundle\DTO;

class ConfigDTO
{
    private $id;
    private $hours;
    private $phone;
    private $image;
    private $email;
    private $app;
    private $terms;
    private $totalWeight;
    private $taxTax;
    private $taxFurniture;
    private $benefit;
    private $ticketPrice;
    private $recalculatePrice;
    private $privacyPolicy;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getHours()
    {
        return $this->hours;
    }

    public function setHours($hours)
    {
        $this->hours = $hours;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getApp()
    {
        return $this->app;
    }

    public function setApp($app)
    {
        $this->app = $app;
    }

    public function getTerms()
    {
        return $this->terms;
    }

    public function setTerms($terms)
    {
        $this->terms = $terms;
    }

    public function getTotalWeight()
    {
        return $this->totalWeight;
    }

    public function setTotalWeight($totalWeight)
    {
        $this->totalWeight = $totalWeight;
    }

    public function getTaxTax()
    {
        return $this->taxTax;
    }

    public function setTaxTax($taxTax)
    {
        $this->taxTax = $taxTax;
    }

    public function getBenefit()
    {
        return $this->benefit;
    }

    public function setBenefit($benefit)
    {
        $this->benefit = $benefit;
    }

    public function getTicketPrice()
    {
        return $this->ticketPrice;
    }

    public function setTicketPrice($ticketPrice)
    {
        $this->ticketPrice = $ticketPrice;
    }

    public function getTaxFurniture()
    {
        return $this->taxFurniture;
    }

    public function setTaxFurniture($taxFurniture)
    {
        $this->taxFurniture = $taxFurniture;
    }

    public function getRecalculatePrice()
    {
        return $this->recalculatePrice;
    }

    public function setRecalculatePrice($recalculatePrice)
    {
        $this->recalculatePrice = $recalculatePrice;
    }

    public function getPrivacyPolicy()
    {
        return $this->privacyPolicy;
    }

    public function setPrivacyPolicy($privacyPolicy)
    {
        $this->privacyPolicy = $privacyPolicy;
    }
}
