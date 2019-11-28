<?php

namespace AppBundle\Entity\Request;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class PreFactureProduct
 */
class PreFactureProduct
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Request\PreFacture", inversedBy="preFactureProducts")
     * @ORM\JoinColumn(name="pre_facture_id", referencedColumnName="id")
     */
    private $preFacture;
    /**
     * @ORM\Column(type="integer")
     */
    private $count;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Offer", inversedBy="preFactures")
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

    public function setPreFacture(PreFacture $preFacture = null)
    {
        $this->preFacture = $preFacture;

        return $this;
    }

    public function getPreFacture()
    {
        return $this->preFacture;
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

    function __toString()
    {
        return $this->product->getName();
    }
}
