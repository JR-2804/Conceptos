<?php

namespace AppBundle\Entity\Request;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class ExternalRequest
 */
class ExternalRequest
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  private $id;
  /**
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Request\ExternalRequestProduct", mappedBy="externalRequest")
   */
  private $externalRequestProducts;
  /**
   * @ORM\Column(type="float")
   */
  private $finalPrice;
  /**
   * @ORM\Column(type="float")
   */
  private $weight;
  /**
   * @ORM\Column(type="float")
   */
  private $budget;
  /**
   * @ORM\Column(type="float")
   */
  private $payment;
  /**
   * @ORM\Column(type="datetime")
   */
  private $creationDate;
  /**
   * @ORM\Column(type="datetime")
   */
  private $acceptDate;
  /**
   * @ORM\Column(type="datetime")
   */
  private $date;
  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="externalRequests")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  private $user;
  /**
   * @ORM\Column(type="string")
   */
  private $state;

  private $remainingTimeFromCreation;

  public function __construct()
  {
    $this->externalRequestProducts = new \Doctrine\Common\Collections\ArrayCollection();
    $this->date = new \DateTime();
  }

  public function getId()
  {
    return $this->id;
  }

  public function addExternalRequestProduct(ExternalRequestProduct $externalRequestProduct)
  {
    $this->externalRequestProducts[] = $externalRequestProduct;

    return $this;
  }

  public function removeExternalRequestProduct(ExternalRequestProduct $externalRequestProduct)
  {
    $this->externalRequestProducts->removeElement($externalRequestProduct);
  }

  public function getExternalRequestProducts()
  {
    return $this->externalRequestProducts;
  }

  public function setFinalPrice($finalPrice)
  {
    $this->finalPrice = $finalPrice;

    return $this;
  }

  public function getFinalPrice()
  {
    return $this->finalPrice;
  }

  public function setWeight($weight)
  {
    $this->weight = $weight;

    return $this;
  }

  public function getWeight()
  {
    return $this->weight;
  }

  public function setBudget($budget)
  {
    $this->budget = $budget;

    return $this;
  }

  public function getBudget()
  {
    return $this->budget;
  }

  public function setPayment($payment)
  {
    $this->payment = $payment;

    return $this;
  }

  public function getPayment()
  {
    return $this->payment;
  }

  public function setCreationDate($creationDate)
  {
      $this->creationDate = $creationDate;

      return $this;
  }

  public function getCreationDate()
  {
      return $this->creationDate;
  }

  public function setAcceptDate($acceptDate)
  {
      $this->acceptDate = $acceptDate;

      return $this;
  }

  public function getAcceptDate()
  {
      return $this->acceptDate;
  }

  public function setDate($date)
  {
      $this->date = $date;

      return $this;
  }

  public function getDate()
  {
      return $this->date;
  }

  public function setUser($user)
  {
    $this->user = $user;

    return $this;
  }

  public function getUser()
  {
    return $this->user;
  }

  public function setState($state)
  {
    $this->state = $state;

    return $this;
  }

  public function getState()
  {
    return $this->state;
  }

  public function setRemainingTimeFromCreation($remainingTimeFromCreation)
  {
    $this->remainingTimeFromCreation = $remainingTimeFromCreation;

    return $this;
  }

  public function getRemainingTimeFromCreation()
  {
    return $this->remainingTimeFromCreation;
  }
}
