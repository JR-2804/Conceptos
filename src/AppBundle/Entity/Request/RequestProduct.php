<?php

namespace AppBundle\Entity\Request;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class RequestProduct
 */
class RequestProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    /**
     * @ORM\Column(type="integer")
     */
    private $productPrice;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Request\Request", inversedBy="requestProducts")
     * @ORM\JoinColumn(name="request_id", referencedColumnName="id")
     */
    private $request;
    /**
     * @ORM\Column(type="integer")
     */
    private $count;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Offer", inversedBy="requests")
     * @ORM\JoinColumn(name="offer_id", referencedColumnName="id")
     */
    private $offer;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isAriplaneForniture;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isAriplaneMattress;
    /**
     * @ORM\Column(type="string")
     */
    private $state;

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

    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProductPrice($productPrice = null)
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getProductPrice()
    {
        return $this->productPrice;
    }

    public function setRequest(Request $request = null)
    {
        $this->request = $request;

        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setOffer(\AppBundle\Entity\Offer $offer = null)
    {
        $this->offer = $offer;

        return $this;
    }

    public function getOffer()
    {
        return $this->offer;
    }

    public function setIsAriplaneForniture($isAriplaneForniture)
    {
        $this->isAriplaneForniture = $isAriplaneForniture;

        return $this;
    }

    public function getIsAriplaneForniture()
    {
        return $this->isAriplaneForniture;
    }

    public function setIsAriplaneMattress($isAriplaneMattress)
    {
        $this->isAriplaneMattress = $isAriplaneMattress;

        return $this;
    }

    public function getIsAriplaneMattress()
    {
        return $this->isAriplaneMattress;
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

    function __toString()
    {
        return $this->product->getName();
    }
}
