<?php

namespace AppBundle\DTO;

class RequestDTO
{
  private $id;
  private $date;
  private $client;
  private $requestProducts;
  private $requestCards;
  private $finalPrice;
  private $logisticCost;
  private $transportCost;
  private $securityCost;
  private $taxes;
  private $discount;
  private $firstClientDiscount;
  private $preFactures;
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

  public function getRequestProducts()
  {
      return $this->requestProducts;
  }

  public function setRequestProducts($requestProducts)
  {
      $this->requestProducts = $requestProducts;
  }

  public function getRequestCards()
  {
      return $this->requestCards;
  }

  public function setRequestCards($requestCards)
  {
      $this->requestCards = $requestCards;
  }

  public function getFinalPrice()
  {
      return $this->finalPrice;
  }

  public function setFinalPrice($finalPrice)
  {
      $this->finalPrice = $finalPrice;
  }

  public function setLogisticCost($logisticCost)
  {
      $this->logisticCost = $logisticCost;
  }

  public function getLogisticCost()
  {
      return $this->logisticCost;
  }

  public function setSecurityCost($securityCost)
  {
      $this->securityCost = $securityCost;
  }

  public function getSecurityCost()
  {
      return $this->securityCost;
  }

  public function setTransportCost($transportCost)
  {
      $this->transportCost = $transportCost;
  }

  public function getTransportCost()
  {
      return $this->transportCost;
  }

  public function setTaxes($taxes)
  {
      $this->taxes = $taxes;
  }

  public function getTaxes()
  {
      return $this->taxes;
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

  public function getPreFactures()
  {
      return $this->preFactures;
  }

  public function setPreFactures($preFactures)
  {
      $this->preFactures = $preFactures;
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
