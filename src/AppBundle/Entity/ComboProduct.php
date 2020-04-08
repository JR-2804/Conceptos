<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class ComboProduct
 */
class ComboProduct
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="comboProducts")
   * @ORM\JoinColumn(name="parent_product_id", referencedColumnName="id")
   */
  private $parentProduct;

  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
   * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
   */
  private $product;
  /**
   * @ORM\Column(type="integer")
   */
  private $count;

  public function getId()
  {
    return $this->id;
  }

  public function setParentProduct(Product $parentProduct = null)
  {
    $this->parentProduct = $parentProduct;

    return $this;
  }

  public function getParentProduct()
  {
    return $this->parentProduct;
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

  public function setCount($count)
  {
      $this->count = $count;

      return $this;
  }

  public function getCount()
  {
      return $this->count;
  }
}
