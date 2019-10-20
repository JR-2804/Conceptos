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
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Request\Request", inversedBy="factures")
     * @ORM\JoinColumn(name="request_id", referencedColumnName="id")
     */
    private $request;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Request\PreFacture", inversedBy="factures")
     * @ORM\JoinColumn(name="pre_facture_id", referencedColumnName="id")
     */
    private $preFacture;

    public function __construct()
    {
        $this->factureProducts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->factureCards = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function setRequest(Request $request = null)
    {
        $this->request = $request;

        return $this;
    }

    public function getRequest()
    {
        return $this->request;
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

    public function calculatePrice($productService) {
      $membershipDiscount = 0;
      $firstClientDiscount = 0;
      $finalPrice = 0;

      $memberNumber = $this->getClient()->getMemberNumber();
      $numberOfClientRequests = 0;
      if ($this->getRequest()) {
        $numberOfClientRequests = $this->getRequest()->getClient()->getRequests()->count();
      }
      if ($this->getPreFacture()) {
        $numberOfClientRequests = $this->getPreFacture()->getClient()->getRequests()->count();
      }

      foreach ($this->getFactureProducts() as $factureProduct) {
        $offer = $factureProduct->getOffer();
        if ($offer && ((!$offer->getOnlyForMembers()) || ($offer->getOnlyForMembers() && $memberNumber))) {
          $price = $factureProduct->getOffer()->getPrice();
        }
        else if ($factureProduct->getIsAriplaneForniture() || $factureProduct->getIsAriplaneMattress()) {
          $price = $productService->calculateProductPrice(
            $factureProduct->getProduct()->getWeight(),
            $factureProduct->getProduct()->getIkeaPrice(),
            $factureProduct->getProduct()->getIsFurniture(),
            $factureProduct->getProduct()->getIsFragile(),
            $factureProduct->getIsAriplaneForniture(),
            $factureProduct->getProduct()->getIsOversize(),
            $factureProduct->getProduct()->getIsTableware(),
            $factureProduct->getProduct()->getIsLamp(),
            $factureProduct->getProduct()->getNumberOfPackages(),
            $factureProduct->getProduct()->getIsMattress(),
            $factureProduct->getIsAriplaneMattress()
          );
        } else {
          $price = $factureProduct->getProduct()->getPrice();
        }
        $finalPrice += $price * $factureProduct->getCount();
      }

      foreach ($this->getFactureCards() as $factureCard) {
        $finalPrice += $factureCard->getPrice() * $factureCard->getCount();
      }

      if ($memberNumber) {
        $membershipDiscount = floor($finalPrice * 0.1);
      } else if ($numberOfClientRequests == 1) {
        $firstClientDiscount = floor($finalPrice * 0.05);
      }

      $finalPrice -= $membershipDiscount;
      $finalPrice -= $firstClientDiscount;
      $finalPrice += $this->getTransportCost();

      $this->setDiscount($membershipDiscount);
      $this->setFirstClientDiscount($firstClientDiscount);
      $this->setFinalPrice($finalPrice);
    }

    function __toString()
    {
        return "Factura: ".$this->id;
    }
}
