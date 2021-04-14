<?php

namespace AppBundle\DTO;

class PreFactureDTO
{
  private $preFactureProducts;
  private $logisticCost;
  private $transportCost;
  private $securityCost;
  private $taxes;

  public function getPreFactureProducts()
  {
    return $this->preFactureProducts;
  }

  public function setPreFactureProducts($preFactureProducts)
  {
    $this->preFactureProducts = $preFactureProducts;
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
}
