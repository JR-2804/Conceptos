<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * @Vich\Uploadable()
 * Class Category
 * @package AppBundle\Entity
 */
class Category
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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Category", mappedBy="subCategories")
     */
    private $parents;
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Category", inversedBy="parents")
     * @ORM\JoinTable(name="category_parent", joinColumns={@ORM\JoinColumn(name="parent_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="child_id", referencedColumnName="id")})
     */
    private $subCategories;
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Product", mappedBy="categories")
     */
    private $products;
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Offer", mappedBy="categories")
     */
    private $offers;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $icon;
    /**
     * @Vich\UploadableField(mapping="category_icon_pictures", fileNameProperty="icon")
     */
    private $iconFile;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;
    /**
     * @Vich\UploadableField(mapping="category_pictures", fileNameProperty="image")
     */
    private $imageFile;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageSite;
    /**
     * @Vich\UploadableField(mapping="category_site_pictures", fileNameProperty="image_site")
     */
    private $imageSiteFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Product", inversedBy="favoritesCategory")
     */
    private $favoritesProducts;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBrand;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $brandDescription;
    /**
     * @ORM\Column(type="integer")
     */
    private $priority;

    public function __construct()
    {
        $this->subCategories = new ArrayCollection();
        $this->parents = new ArrayCollection();
        $this->favoritesProducts = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->offers = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setParent(\AppBundle\Entity\Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function addSubCategory(\AppBundle\Entity\Category $subCategory)
    {
        $this->subCategories[] = $subCategory;

        return $this;
    }

    public function removeSubCategory(\AppBundle\Entity\Category $subCategory)
    {
        $this->subCategories->removeElement($subCategory);
    }

    public function getSubCategories()
    {
        return $this->subCategories;
    }

    function __toString()
    {
        return $this->name;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getIconFile()
    {
        return $this->iconFile;
    }

    public function setIconFile(File $iconFile = null)
    {
        $this->iconFile = $iconFile;
        if ($iconFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile(File $imageFile = null)
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageSiteFile()
    {
        return $this->imageSiteFile;
    }

    public function setImageSiteFile(File $imageSiteFile = null)
    {
        $this->imageSiteFile = $imageSiteFile;
        if ($imageSiteFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }


    public function addParent(\AppBundle\Entity\Category $parent)
    {
        $this->parents[] = $parent;

        return $this;
    }

    public function removeParent(\AppBundle\Entity\Category $parent)
    {
        $this->parents->removeElement($parent);
    }

    public function getParents()
    {
        return $this->parents;
    }

    public function addFavoritesProduct(\AppBundle\Entity\Product $favoritesProduct)
    {
        $this->favoritesProducts[] = $favoritesProduct;

        return $this;
    }

    public function removeFavoritesProduct(\AppBundle\Entity\Product $favoritesProduct)
    {
        $this->favoritesProducts->removeElement($favoritesProduct);
    }

    public function getFavoritesProducts()
    {
        return $this->favoritesProducts;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setImageSite($imageSite)
    {
        $this->imageSite = $imageSite;

        return $this;
    }

    public function getImageSite()
    {
        return $this->imageSite;
    }

    public function addProduct(\AppBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    public function removeProduct(\AppBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function addOffer($offer)
    {
        $this->offers[] = $offer;

        return $this;
    }

    public function removeOffer($offer)
    {
        $this->offers->removeElement($offer);
    }

    public function getOffers()
    {
        return $this->offers;
    }

    public function setIsBrand($isBrand)
    {
        $this->isBrand = $isBrand;

        return $this;
    }

    public function getIsBrand()
    {
        return $this->isBrand;
    }

    public function setBrandDescription($brandDescription)
    {
        $this->brandDescription = $brandDescription;

        return $this;
    }

    public function getBrandDescription()
    {
        return $this->brandDescription;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    public function getPriority()
    {
        return $this->priority;
    }
}
