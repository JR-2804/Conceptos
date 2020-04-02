<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class Color
 * @package AppBundle\Entity
 */
class Color
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
  private $name;
  /**
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="color")
   */
  private $products;

  /**
   * Color constructor.
   */
  public function __construct()
  {
    $this->products = new ArrayCollection();
  }

  public function getId()
  {
    return $this->id;
  }

  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  public function getName()
  {
    return $this->name;
  }

  public function addProduct(\AppBundle\Entity\Product $product)
  {
    $this->products[] = $product;

    return $this;
  }

  public function removeProduct(\AppBundle\Entity\Product $product)
  {
    $this->products->removeElement($product);
  }

  public function getProducts()
  {
    return $this->products;
  }

  function __toString()
  {
    return $this->name;
  }
}
