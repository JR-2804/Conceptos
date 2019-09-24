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
     * @ORM\Column(type="float")
     */
    private $evaluationValue;
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

    public function setEvaluationValue($evaluationValue)
    {
        $this->evaluationValue = $evaluationValue;

        return $this;
    }

    public function getEvaluationValue()
    {
        return $this->evaluationValue;
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
