<?php

namespace AppBundle\Entity\Request;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class Client
 * @package AppBundle\Entity\Request
 */
class Client
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
    private $name;
    /**
     * @ORM\Column(type="string")
     */
    private $address;
    /**
     * @ORM\Column(type="string")
     */
    private $email;
    /**
     * @ORM\Column(type="string")
     */
    private $movil;
    /**
     * @ORM\Column(type="string")
     */
    private $phone;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $memberNumber;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Request\Request", mappedBy="client")
     */
    private $requests;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->requests = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Client
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Client
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set movil
     *
     * @param string $movil
     *
     * @return Client
     */
    public function setMovil($movil)
    {
        $this->movil = $movil;

        return $this;
    }

    /**
     * Get movil
     *
     * @return string
     */
    public function getMovil()
    {
        return $this->movil;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Client
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Add request
     *
     * @param \AppBundle\Entity\Request\Request $request
     *
     * @return Client
     */
    public function addRequest(\AppBundle\Entity\Request\Request $request)
    {
        $this->requests[] = $request;

        return $this;
    }

    /**
     * Remove request
     *
     * @param \AppBundle\Entity\Request\Request $request
     */
    public function removeRequest(\AppBundle\Entity\Request\Request $request)
    {
        $this->requests->removeElement($request);
    }

    /**
     * Get requests
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * Set memberNumber
     *
     * @param string $memberNumber
     *
     * @return Client
     */
    public function setMemberNumber($memberNumber)
    {
        $this->memberNumber = $memberNumber;

        return $this;
    }

    /**
     * Get memberNumber
     *
     * @return string
     */
    public function getMemberNumber()
    {
        return $this->memberNumber;
    }

    function __toString()
    {
        return $this->name;
    }
}
