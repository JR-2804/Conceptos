<?php

namespace AppBundle\Entity\Request;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class Request
 */
class Request
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Request\Client", inversedBy="requests")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Request\RequestProduct", mappedBy="request")
     */
    private $requestProducts;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Request\RequestCard", mappedBy="request")
     */
    private $requestCards;
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Request\PreFacture", mappedBy="request")
     */
    private $preFactures;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Request\Facture", mappedBy="request")
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

    public function __construct()
    {
        $this->requestProducts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requestCards = new \Doctrine\Common\Collections\ArrayCollection();
        $this->preFactures = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function addRequestProduct(RequestProduct $requestProduct)
    {
        $this->requestProducts[] = $requestProduct;

        return $this;
    }

    public function removeRequestProduct(RequestProduct $requestProduct)
    {
        $this->requestProducts->removeElement($requestProduct);
    }

    public function getRequestProducts()
    {
        return $this->requestProducts;
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

    public function addRequestCard(RequestCard $requestCard)
    {
        $this->requestCards[] = $requestCard;

        return $this;
    }

    public function removeRequestCard(RequestCard $requestCard)
    {
        $this->requestCards->removeElement($requestCard);
    }

    public function getRequestCards()
    {
        return $this->requestCards;
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

    public function addPreFacture(PreFacture $preFacture)
    {
        $this->preFactures[] = $preFacture;

        return $this;
    }

    public function removePreFacture(PreFacture $preFacture)
    {
        $this->preFactures->removeElement($preFacture);
    }

    public function getPreFactures()
    {
        return $this->preFactures;
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
      $numberOfClientRequests = $this->getClient()->getRequests()->count();

      foreach ($this->getRequestProducts() as $requestProduct) {
        $offer = $requestProduct->getOffer();
        if ($offer && ((!$offer->getOnlyForMembers()) || ($offer->getOnlyForMembers() && $memberNumber))) {
          $price = $requestProduct->getOffer()->getPrice();
        }
        else if ($requestProduct->getIsAriplaneForniture() || $requestProduct->getIsAriplaneMattress()) {
          $price = $productService->calculateProductPrice(
            $requestProduct->getProduct()->getWeight(),
            $requestProduct->getProduct()->getIkeaPrice(),
            $requestProduct->getProduct()->getIsFurniture(),
            $requestProduct->getProduct()->getIsFragile(),
            $requestProduct->getIsAriplaneForniture(),
            $requestProduct->getProduct()->getIsOversize(),
            $requestProduct->getProduct()->getIsTableware(),
            $requestProduct->getProduct()->getIsLamp(),
            $requestProduct->getProduct()->getNumberOfPackages(),
            $requestProduct->getProduct()->getIsMattress(),
            $requestProduct->getIsAriplaneMattress(),
            $requestProduct->getProduct()->getIsFaucet(),
            $requestProduct->getProduct()->getIsGrill(),
            $requestProduct->getProduct()->getIsShelf(),
            $requestProduct->getProduct()->getIsDesk(),
            $requestProduct->getProduct()->getIsBookcase(),
            $requestProduct->getProduct()->getIsComoda(),
            $requestProduct->getProduct()->getIsRepisa()
          );
        } else {
          $price = $requestProduct->getProduct()->getPrice();
        }
        $finalPrice += $price * $requestProduct->getCount();
      }

      foreach ($this->getRequestCards() as $requestCard) {
        $finalPrice += $requestCard->getPrice() * $requestCard->getCount();
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

    function __toString()
    {
        return "Pedido: ".$this->id;
    }
}
