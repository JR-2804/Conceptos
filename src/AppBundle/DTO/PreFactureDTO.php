<?php

namespace AppBundle\DTO;

class PreFactureDTO
{
  private $preFactureProducts;

  public function getPreFactureProducts()
  {
    return $this->preFactureProducts;
  }

  public function setPreFactureProducts($preFactureProducts)
  {
    $this->preFactureProducts = $preFactureProducts;
  }
}
