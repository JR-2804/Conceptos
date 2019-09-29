<?php

namespace AppBundle\Entity\Request;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class PreFacture
 */
class PreFacture
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Request\Client", inversedBy="preFactures")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Request\PreFactureProduct", mappedBy="preFacture")
     */
    private $preFactureProducts;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Request\PreFactureCard", mappedBy="preFacture")
     */
    private $preFactureCards;
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
        $this->preFactureProducts = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function addPreFactureProduct(PreFactureProduct $preFactureProduct)
    {
        $this->preFactureProducts[] = $preFactureProduct;

        return $this;
    }

    public function removePreFactureProduct(PreFactureProduct $preFactureProduct)
    {
        $this->preFactureProducts->removeElement($preFactureProduct);
    }

    public function getPreFactureProducts()
    {
        return $this->preFactureProducts;
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

    public function addPreFactureCard(PreFactureCard $preFactureCard)
    {
        $this->preFactureCards[] = $preFactureCard;

        return $this;
    }

    public function removePreFactureCard(PreFactureCard $preFactureCard)
    {
        $this->preFactureCards->removeElement($preFactureCard);
    }

    public function getPreFactureCards()
    {
        return $this->preFactureCards;
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
        return "Prefactura: ".$this->id;
    }
}
