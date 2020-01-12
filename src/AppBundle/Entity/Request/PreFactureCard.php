<?php

namespace AppBundle\Entity\Request;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class PreFactureCard
 */
class PreFactureCard
{
    const CARD15 = 'CARD-15';
    const CARD35 = 'CARD-35';
    const CARD50 = 'CARD-50';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $count;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Request\PreFacture", inversedBy="preFactureCards")
     * @ORM\JoinColumn(name="pre_facture_id", referencedColumnName="id")
     */
    private $preFacture;
    /**
     * @ORM\Column(type="integer")
     */
    private $price;
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

    public function setPreFacture(PreFacture $preFacture = null)
    {
        $this->preFacture = $preFacture;

        return $this;
    }

    public function getPreFacture()
    {
        return $this->preFacture;
    }

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
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
        return "Tarjeta";
    }
}
