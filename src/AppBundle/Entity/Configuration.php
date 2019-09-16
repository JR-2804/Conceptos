<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * @Vich\Uploadable()
 * Class Configuration
 */
class Configuration
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
    private $hours;
    /**
     * @ORM\Column(type="string")
     */
    private $phone;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    private $image;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastProductUpdate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastDatabaseExport;
    /**
     * @ORM\Column(type="text")
     */
    private $termAndConditions;
    /**
     * @ORM\Column(type="text")
     */
    private $privacyPolicy;
    /**
     * @ORM\Column(type="string")
     */
    private $lastDBExported;
    /**
     * @ORM\Column(type="float")
     */
    private $totalWeight;
    /**
     * @ORM\Column(type="integer")
     */
    private $taxTax;
    /**
     * @ORM\Column(type="float")
     */
    private $benefit;
    /**
     * @ORM\Column(type="float")
     */
    private $ticketPrice;
    /**
     * @ORM\Column(type="float")
     */
    private $taxFurniture;

    public function getId()
    {
        return $this->id;
    }

    public function setHours($hours)
    {
        $this->hours = $hours;

        return $this;
    }

    public function getHours()
    {
        return $this->hours;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setImage(Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setLastProductUpdate($lastProductUpdate)
    {
        $this->lastProductUpdate = $lastProductUpdate;

        return $this;
    }

    public function getLastProductUpdate()
    {
        return $this->lastProductUpdate;
    }

    public function setLastDatabaseExport($lastDatabaseExport)
    {
        $this->lastDatabaseExport = $lastDatabaseExport;

        return $this;
    }

    public function getLastDatabaseExport()
    {
        return $this->lastDatabaseExport;
    }

    public function setTermAndConditions($termAndConditions)
    {
        $this->termAndConditions = $termAndConditions;

        return $this;
    }

    public function getTermAndConditions()
    {
        return $this->termAndConditions;
    }

    public function setPrivacyPolicy($privacyPolicy)
    {
        $this->privacyPolicy = $privacyPolicy;

        return $this;
    }

    public function getPrivacyPolicy()
    {
        return $this->privacyPolicy;
    }

    public function setLastDBExported($lastDBExported)
    {
        $this->lastDBExported = $lastDBExported;

        return $this;
    }

    public function getLastDBExported()
    {
        return $this->lastDBExported;
    }

    public function setTotalWeight($totalWeight)
    {
        $this->totalWeight = $totalWeight;

        return $this;
    }

    public function getTotalWeight()
    {
        return $this->totalWeight;
    }

    public function setTaxTax($taxTax)
    {
        $this->taxTax = $taxTax;

        return $this;
    }

    public function getTaxTax()
    {
        return $this->taxTax;
    }

    public function setBenefit($benefit)
    {
        $this->benefit = $benefit;

        return $this;
    }

    public function getBenefit()
    {
        return $this->benefit;
    }

    public function setTicketPrice($ticketPrice)
    {
        $this->ticketPrice = $ticketPrice;

        return $this;
    }

    public function getTicketPrice()
    {
        return $this->ticketPrice;
    }

    public function setTaxFurniture($taxFurniture)
    {
        $this->taxFurniture = $taxFurniture;

        return $this;
    }

    public function getTaxFurniture()
    {
        return $this->taxFurniture;
    }
}
