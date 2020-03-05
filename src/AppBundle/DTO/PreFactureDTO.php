<?php

namespace AppBundle\DTO;

class PreFactureDTO
{
  private $id;
  private $preFactureProducts;
  private $preFactureCards;
  private $finalPrice;
  private $transportCost;
  private $discount;
  private $firstClientDiscount;
  private $request;
  private $factures;
  private $twoStepExtra;
  private $cucExtra;

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

  public function getPreFactureProducts()
  {
      return $this->preFactureProducts;
  }

  public function setPreFactureProducts($preFactureProducts)
  {
      $this->preFactureProducts = $preFactureProducts;
  }

  public function getPreFactureCards()
  {
      return $this->preFactureCards;
  }

  public function setPreFactureCards($preFactureCards)
  {
      $this->preFactureCards = $preFactureCards;
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

  public function getRequest()
  {
      return $this->request;
  }

  public function setRequest($request)
  {
      $this->request = $request;
  }

  public function getFactures()
  {
      return $this->factures;
  }

  public function setFactures($factures)
  {
      $this->factures = $factures;
  }

  public function setTwoStepExtra($twoStepExtra)
  {
      $this->twoStepExtra = $twoStepExtra;
  }

  public function getTwoStepExtra()
  {
      return $this->twoStepExtra;
  }

  public function setCucExtra($cucExtra)
  {
      $this->cucExtra = $cucExtra;
  }

  public function getCucExtra()
  {
      return $this->cucExtra;
  }
}
