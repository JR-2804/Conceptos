<?php

namespace AppBundle\Entity\Request;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class RequestCard
 */
class RequestCard
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Request\Request", inversedBy="requestCards")
     * @ORM\JoinColumn(name="request_id", referencedColumnName="id")
     */
    private $request;
    /**
     * @ORM\Column(type="integer")
     */
    private $price;

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

    public function setRequest(Request $request = null)
    {
        $this->request = $request;

        return $this;
    }

    public function getRequest()
    {
        return $this->request;
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

    function __toString()
    {
        return "Tarjeta";
    }
}
