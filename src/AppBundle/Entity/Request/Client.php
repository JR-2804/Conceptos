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
    private $firstName;
    /**
     * @ORM\Column(type="string")
     */
    private $lastName;
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

    public function __construct()
    {
        $this->requests = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
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

    public function setMovil($movil)
    {
        $this->movil = $movil;

        return $this;
    }

    public function getMovil()
    {
        return $this->movil;
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

    public function addRequest(\AppBundle\Entity\Request\Request $request)
    {
        $this->requests[] = $request;

        return $this;
    }

    public function removeRequest(\AppBundle\Entity\Request\Request $request)
    {
        $this->requests->removeElement($request);
    }

    public function getRequests()
    {
        return $this->requests;
    }

    public function setMemberNumber($memberNumber)
    {
        $this->memberNumber = $memberNumber;

        return $this;
    }

    public function getMemberNumber()
    {
        return $this->memberNumber;
    }

    function __toString()
    {
        return $this->firstName.' '.$this->lastName;
    }
}
