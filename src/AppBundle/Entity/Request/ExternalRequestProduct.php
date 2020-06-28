<?php

namespace AppBundle\Entity\Request;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class ExternalRequestProduct
 */
class ExternalRequestProduct
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  private $id;
  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
   * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
   */
  private $product;
  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Request\ExternalRequest", inversedBy="externalRequestProducts")
   * @ORM\JoinColumn(name="external_request_id", referencedColumnName="id")
   */
  private $externalRequest;
  /**
   * @ORM\Column(type="integer")
   */
  private $count;

  public function getId()
  {
    return $this->id;
  }

  public function setCount($count)
  {
    $this->count = $count;

    return $this;
  }

  public function getCount()
  {
    return $this->count;
  }

  public function setProduct(\AppBundle\Entity\Product $product = null)
  {
    $this->product = $product;

    return $this;
  }

  public function getProduct()
  {
    return $this->product;
  }

  public function setProductPrice($productPrice = null)
  {
    $this->productPrice = $productPrice;

    return $this;
  }

  public function getProductPrice()
  {
    return $this->productPrice;
  }

  public function setExternalRequest(ExternalRequest $externalRequest = null)
  {
    $this->externalRequest = $externalRequest;

    return $this;
  }

  public function getExternalRequest()
  {
    return $this->externalRequest;
  }
}
