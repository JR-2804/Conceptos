<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class Member
 */
class Member
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
    private $email;
    /**
     * @ORM\Column(type="string")
     */
    private $phone;
    /**
     * @ORM\Column(type="string")
     */
    private $number;
    /**
     * @ORM\Column(type="string")
     */
    private $address;
    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="member")
     */
    private $user;
    /**
     * @ORM\Column(type="integer")
     */
    private $balance;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BalanceUpdate", mappedBy="member")
     */
    private $balanceUpdates;
    /**
     * @ORM\Column(type="date")
     */
    private $date;

    public function __construct()
    {
        $this->balanceUpdates = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
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

    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
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

    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function addBalanceUpdate($balanceUpdate)
    {
        $this->balanceUpdates[] = $balanceUpdate;

        return $this;
    }

    public function removeBalanceUpdate($balanceUpdate)
    {
        $this->balanceUpdates->removeElement($balanceUpdate);
    }

    public function getBalanceUpdates()
    {
        return $this->balanceUpdates;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }
}
