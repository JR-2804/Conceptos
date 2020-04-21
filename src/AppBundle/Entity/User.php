<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity()
 * @ORM\Table(name="fos_user")
 * Class User
 */
class User extends BaseUser
{
    public $jsonImage;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Member", mappedBy="user", cascade={"remove"})
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id", nullable=true)
     */
    private $member;
    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image", cascade={"remove"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true)
     */
    private $image;
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
    private $mobileNumber;
    /**
     * @ORM\Column(type="string")
     */
    private $homeNumber;
    /**
     * @ORM\Column(type="date")
     */
    private $birthDate;
    /**
     * @ORM\Column(type="string")
     */
    private $address;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FavoriteProduct", mappedBy="user")
     */
    private $favorites;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Evaluation", mappedBy="user")
     */
    private $evaluations;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ShopCartProduct", mappedBy="user")
     */
    private $shopCartProducts;

    public function __construct()
    {
        parent::__construct();
        $this->favorites = new \Doctrine\Common\Collections\ArrayCollection();
        $this->evaluations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->shopCartProducts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return parent::getEmail();
    }

    public function setMember(Member $member = null)
    {
        $this->member = $member;

        return $this;
    }

    public function getMember()
    {
        return $this->member;
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

    public function setMobileNumber($mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    public function setHomeNumber($homeNumber)
    {
        $this->homeNumber = $homeNumber;

        return $this;
    }

    public function getHomeNumber()
    {
        return $this->homeNumber;
    }

    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getBirthDate()
    {
        return $this->birthDate;
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

    public function addFavorite(FavoriteProduct $favorite)
    {
        $this->favorites[] = $favorite;

        return $this;
    }

    public function removeFavorite(FavoriteProduct $favorite)
    {
        $this->favorites->removeElement($favorite);
    }

    public function getFavorites()
    {
        return $this->favorites;
    }

    public function addEvaluation(Evaluation $evaluation)
    {
        $this->evaluations[] = $evaluation;

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation)
    {
        $this->evaluations->removeElement($evaluation);
    }

    public function getEvaluations()
    {
        return $this->evaluations;
    }

    public function getProductsInCartCount()
    {
      return count($this->shopCartProducts);
    }

    public function getFavoritesCount()
    {
      return count($this->favorites);
    }
}
