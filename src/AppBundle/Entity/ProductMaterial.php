<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class ProductMaterial
 * @package AppBundle\Entity
 */
class ProductMaterial
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
  private $material;
  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="materials")
   * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
   */
  private $product;

  public function getId()
  {
    return $this->id;
  }

  public function setMaterial($material)
  {
    $this->material = $material;

    return $this;
  }

  public function getMaterial()
  {
    return $this->material;
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
