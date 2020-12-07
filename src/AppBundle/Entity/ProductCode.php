<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class ProductCode
 * @package AppBundle\Entity
 */
class ProductCode
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  private $id;
  /**
   * @ORM\Column(type="string")
   */
  private $code;
  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="codes")
   * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
   */
  private $product;

  public function getId()
  {
    return $this->id;
  }

  public function setCode($code)
  {
    $this->code = $code;

    return $this;
  }

  public function getCode()
  {
    return $this->code;
  }

  public function setProduct(Product $product = null)
  {
    $this->product = $product;

    return $this;
  }

  public function getProduct()
  {
    return $this->product;
  }
}
