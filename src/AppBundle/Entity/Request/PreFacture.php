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
    /**
     * @ORM\Column(type="float")
     */
    private $comboDiscount;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Request\Request", inversedBy="preFactures")
     * @ORM\JoinColumn(name="request_id", referencedColumnName="id")
     */
    private $request;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Request\Facture", mappedBy="preFacture")
     */
    private $factures;
    /**
     * @ORM\Column(type="float")
     */
    private $twoStepExtra;
    /**
     * @ORM\Column(type="float")
     */
    private $cucExtra;
    /**
     * @ORM\Column(type="float")
     */
    private $bagsExtra;

    public function __construct()
    {
        $this->preFactureProducts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->preFactureCards = new \Doctrine\Common\Collections\ArrayCollection();
        $this->factures = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function setComboDiscount($comboDiscount)
    {
        $this->comboDiscount = $comboDiscount;

        return $this;
    }

    public function getComboDiscount()
    {
        return $this->comboDiscount;
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

    public function addFacture(Facture $facture)
    {
        $this->factures[] = $facture;

        return $this;
    }

    public function removeFacture(Facture $facture)
    {
        $this->facture->removeElement($facture);
    }

    public function getFactures()
    {
        return $this->factures;
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

      foreach ($this->getPreFactureProducts() as $prefactureProduct) {
        $offer = $prefactureProduct->getOffer();
        if ($offer && ((!$offer->getOnlyForMembers()) || ($offer->getOnlyForMembers() && $memberNumber))) {
          $price = $prefactureProduct->getOffer()->getPrice();
        }
        else if ($prefactureProduct->getIsAriplaneForniture() || $prefactureProduct->getIsAriplaneMattress()) {
          $price = $productService->calculateProductPrice(
            $prefactureProduct->getProduct()->getWeight(),
            $prefactureProduct->getProduct()->getIkeaPrice(),
            $prefactureProduct->getProduct()->getIsFurniture(),
            $prefactureProduct->getProduct()->getIsFragile(),
            $prefactureProduct->getIsAriplaneForniture(),
            $prefactureProduct->getProduct()->getIsOversize(),
            $prefactureProduct->getProduct()->getIsTableware(),
            $prefactureProduct->getProduct()->getIsLamp(),
            $prefactureProduct->getProduct()->getNumberOfPackages(),
            $prefactureProduct->getProduct()->getIsMattress(),
            $prefactureProduct->getIsAriplaneMattress(),
            $prefactureProduct->getProduct()->getIsFaucet(),
            $prefactureProduct->getProduct()->getIsGrill(),
            $prefactureProduct->getProduct()->getIsShelf(),
            $prefactureProduct->getProduct()->getIsDesk(),
            $prefactureProduct->getProduct()->getIsBookcase(),
            $prefactureProduct->getProduct()->getIsComoda(),
            $prefactureProduct->getProduct()->getIsRepisa()
          );
        } else {
          $price = $prefactureProduct->getProduct()->getPrice();
        }
        $finalPrice += $price * $prefactureProduct->getCount();
      }

      foreach ($this->getPreFactureCards() as $prefactureCard) {
        $finalPrice += $prefactureCard->getPrice() * $prefactureCard->getCount();
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

    public function setTwoStepExtra($twoStepExtra)
    {
        $this->twoStepExtra = $twoStepExtra;

        return $this;
    }

    public function getTwoStepExtra()
    {
        return $this->twoStepExtra;
    }

    public function setCucExtra($cucExtra)
    {
        $this->cucExtra = $cucExtra;

        return $this;
    }

    public function getCucExtra()
    {
        return $this->cucExtra;
    }

    public function setBagsExtra($bagsExtra)
    {
        $this->bagsExtra = $bagsExtra;

        return $this;
    }

    public function getBagsExtra()
    {
        return $this->bagsExtra;
    }

    function __toString()
    {
        return "Prefactura: ".$this->id;
    }
}
