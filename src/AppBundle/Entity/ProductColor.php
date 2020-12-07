<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class ProductColor
 * @package AppBundle\Entity
 */
class ProductColor
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
  private $color;
  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="colors")
   * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
   */
  private $product;

  public function getId()
  {
    return $this->id;
  }

  public function setColor($color)
  {
    $this->color = $color;

    return $this;
  }

  public function getColor()
  {
    return $this->color;
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
