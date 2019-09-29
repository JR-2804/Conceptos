<?php

namespace AppBundle\Entity\Request;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class Facture
 */
class Facture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="datetime")
     */
    private $date;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Request\Client", inversedBy="factures")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Request\FactureProduct", mappedBy="facture")
     */
    private $factureProducts;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Request\FactureCard", mappedBy="facture")
     */
    private $factureCards;
    /**
     * @ORM\Column(type="float")
     */
    private $finalPrice;
    /**
     * @ORM\Column(type="float")
     */
    private $transportCost;
    /**
     * @ORM\Column(type="float")
     */
    private $discount;
    /**
     * @ORM\Column(type="float")
     */
    private $firstClientDiscount;

    public function __construct()
    {
        $this->factureProducts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
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

    public function setClient(Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function addFactureProduct(FactureProduct $factureProduct)
    {
        $this->factureProducts[] = $factureProduct;

        return $this;
    }

    public function removeFactureProduct(FactureProduct $factureProduct)
    {
        $this->factureProducts->removeElement($factureProduct);
    }

    public function getFactureProducts()
    {
        return $this->factureProducts;
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

    public function getClientEmail()
    {
        return $this->client->getEmail();
    }

    public function addFactureCard(FactureCard $factureCard)
    {
        $this->factureCards[] = $factureCard;

        return $this;
    }

    public function removeFactureCard(FactureCard $factureCard)
    {
        $this->factureCards->removeElement($factureCard);
    }

    public function getFactureCards()
    {
        return $this->factureCards;
    }

    public function setTransportCost($transportCost)
    {
        $this->transportCost = $transportCost;

        return $this;
    }

    public function getTransportCost()
    {
        return $this->transportCost;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setFirstClientDiscount($firstClientDiscount)
    {
        $this->firstClientDiscount = $firstClientDiscount;

        return $this;
    }

    public function getFirstClientDiscount()
    {
        return $this->firstClientDiscount;
    }

    function __toString()
    {
        return "Factura: ".$this->id;
    }
}
