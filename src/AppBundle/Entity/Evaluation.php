<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class Evaluation
 */
class Evaluation
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
  private $generalOpinion;
  /**
   * @ORM\Column(type="float")
   */
  private $evaluationValue;
  /**
   * @ORM\Column(type="float")
   */
  private $priceQualityValue;
  /**
   * @ORM\Column(type="float")
   */
  private $utilityValue;
  /**
   * @ORM\Column(type="float")
   */
  private $durabilityValue;
  /**
   * @ORM\Column(type="float")
   */
  private $qualityValue;
  /**
   * @ORM\Column(type="float")
   */
  private $designValue;
  /**
   * @ORM\Column(type="string")
   */
  private $comment;
  /**
   * @ORM\Column(type="boolean")
   */
  private $isRecommended;
  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="evaluations")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  private $user;
  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="userFavorites")
   * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
   */
  private $product;

  public function getId()
  {
    return $this->id;
  }

  public function setGeneralOpinion($generalOpinion)
  {
    $this->generalOpinion = $generalOpinion;

    return $this;
  }

  public function getGeneralOpinion()
  {
    return $this->generalOpinion;
  }

  public function setEvaluationValue($evaluationValue)
  {
    $this->evaluationValue = $evaluationValue;

    return $this;
  }

  public function getEvaluationValue()
  {
    return $this->evaluationValue;
  }

  public function setPriceQualityValue($priceQualityValue)
  {
    $this->priceQualityValue = $priceQualityValue;

    return $this;
  }

  public function getPriceQualityValue()
  {
    return $this->priceQualityValue;
  }

  public function setUtilityValue($utilityValue)
  {
    $this->utilityValue = $utilityValue;

    return $this;
  }

  public function getUtilityValue()
  {
    return $this->utilityValue;
  }

  public function setDurabilityValue($durabilityValue)
  {
    $this->durabilityValue = $durabilityValue;

    return $this;
  }

  public function getDurabilityValue()
  {
    return $this->durabilityValue;
  }

  public function setQualityValue($qualityValue)
  {
    $this->qualityValue = $qualityValue;

    return $this;
  }

  public function getQualityValue()
  {
    return $this->qualityValue;
  }

  public function setDesignValue($designValue)
  {
    $this->designValue = $designValue;

    return $this;
  }

  public function getDesignValue()
  {
    return $this->designValue;
  }

  public function setComment($comment)
  {
    $this->comment = $comment;

    return $this;
  }

  public function getComment()
  {
    return $this->comment;
  }

  public function setIsRecommended($isRecommended)
  {
    $this->isRecommended = $isRecommended;

    return $this;
  }

  public function getIsRecommended()
  {
    return $this->isRecommended;
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
