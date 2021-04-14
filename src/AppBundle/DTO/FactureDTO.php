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
  private $logisticCost;
  private $transportCost;
  private $securityCost;
  private $taxes;
  private $discount;
  private $firstClientDiscount;
  private $request;
  private $preFacture;
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

  public function getRequest()
  {
      return $this->request;
  }

  public function setRequest($request)
  {
      $this->request = $request;
  }

  public function getPreFacture()
  {
      return $this->preFacture;
  }

  public function setPreFacture($preFacture)
  {
      $this->preFacture = $preFacture;
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
