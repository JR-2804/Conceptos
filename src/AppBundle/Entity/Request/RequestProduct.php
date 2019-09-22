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

    function __toString()
    {
        return $this->product->getName();
    }
}
