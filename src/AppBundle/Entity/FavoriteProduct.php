<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class FavoriteProduct
 */
class FavoriteProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="favorites")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="userFavorites")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    /**
     * @ORM\Column(type="date")
     */
    private $date;

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

    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct()
    {
        return $this->product;
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
}
