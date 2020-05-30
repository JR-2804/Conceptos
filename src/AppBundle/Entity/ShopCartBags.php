<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class ShopCartBags
 */
class ShopCartBags
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
  private $numberOfPayedBags;
  /**
   * @ORM\Column(type="integer")
   */
  private $numberOfFreeBags;
  /**
   * @ORM\OneToOne(targetEntity="AppBundle\Entity\User")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  private $user;

  public function getId()
  {
    return $this->id;
  }

  public function setNumberOfPayedBags($numberOfPayedBags)
  {
    $this->numberOfPayedBags = $numberOfPayedBags;

    return $this;
  }

  public function getNumberOfPayedBags()
  {
    return $this->numberOfPayedBags;
  }

  public function setNumberOfFreeBags($numberOfFreeBags)
  {
    $this->numberOfFreeBags = $numberOfFreeBags;

    return $this;
  }

  public function getNumberOfFreeBags()
  {
    return $this->numberOfFreeBags;
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
