<?php

namespace AppBundle\DTO;

class FactureDTO
{
  private $id;
  private $date;
  private $client;
  private $factureProducts;
  private $factureCards;
  private $finalPrice;
  private $transportCost;
  private $discount;
  private $firstClientDiscount;

  public function getId()
  {
      return $this->id;
  }

  public function setId($id)
  {
      $this->id = $id;
  }

  public function getDate()
  {
      return $this->date;
  }

  public function setDate($date)
  {
      $this->date = $date;
  }

  public function getClient()
  {
      return $this->client;
  }

  public function setClient($client)
  {
      $this->client = $client;
  }

  public function getFactureProducts()
  {
      return $this->factureProducts;
  }

  public function setFactureProducts($factureProducts)
  {
      $this->factureProducts = $factureProducts;
  }

  public function getFactureCards()
  {
      return $this->factureCards;
  }

  public function setFactureCards($factureCards)
  {
      $this->factureCards = $factureCards;
  }

  public function getFinalPrice()
  {
      return $this->finalPrice;
  }

  public function setFinalPrice($finalPrice)
  {
      $this->finalPrice = $finalPrice;
  }

  public function getTransportCost()
  {
      return $this->transportCost;
  }

  public function setTransportCost($transportCost)
  {
      $this->transportCost = $transportCost;
  }

  public function getDiscount()
  {
      return $this->discount;
  }

  public function setDiscount($discount)
  {
      $this->discount = $discount;
  }

  public function getFirstClientDiscount()
  {
      return $this->firstClientDiscount;
  }

  public function setFirstClientDiscount($firstClientDiscount)
  {
      $this->firstClientDiscount = $firstClientDiscount;
  }
}
