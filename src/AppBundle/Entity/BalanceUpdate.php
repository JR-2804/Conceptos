<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class BalanceUpdate
 */
class BalanceUpdate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Member", inversedBy="balanceUpdates")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $member;
    /**
     * @ORM\Column(type="datetime")
     */
    private $date;
    /**
     * @ORM\Column(type="integer")
     */
    private $balance;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function setMember($member = null)
    {
        $this->member = $member;

        return $this;
    }

    public function getMember()
    {
        return $this->member;
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

    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    public function getBalance()
    {
        return $this->balance;
    }
}
