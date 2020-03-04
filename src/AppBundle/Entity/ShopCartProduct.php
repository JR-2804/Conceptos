<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class ShopCartProduct
 */
class ShopCartProduct
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  private $id;
  /**
   * @ORM\Column(type="integer")
   */
  private $count;
  /**
   * @ORM\Column(type="text")
   */
  private $uuid;
  /**
   * @ORM\Column(type="text")
   */
  private $productId;
  /**
   * @ORM\OneToOne(targetEntity="AppBundle\Entity\User")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  private $user;

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

  public function setUuid($uuid)
  {
    $this->uuid = $uuid;

    return $this;
  }

  public function getUuid()
  {
    return $this->uuid;
  }

  public function setProductId($productId)
  {
    $this->productId = $productId;

    return $this;
  }

  public function getProductId()
  {
    return $this->productId;
  }

  public function setUser(User $user = null)
  {
    $this->user = $user;

    return $this;
  }

  public function getUser()
  {
    return $this->user;
  }
}
