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
     * Category constructor.
     */
    public function __construct()
    {
        $this->subCategories = new ArrayCollection();
        $this->parents = new ArrayCollection();
        $this->favoritesProducts = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->offers = new ArrayCollection();
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
     * @return Category
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
     * Set parent
     *
     * @param \AppBundle\Entity\Category $parent
     *
     * @return Category
     */
    public function setParent(\AppBundle\Entity\Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add subCategory
     *
     * @param \AppBundle\Entity\Category $subCategory
     *
     * @return Category
     */
    public function addSubCategory(\AppBundle\Entity\Category $subCategory)
    {
        $this->subCategories[] = $subCategory;

        return $this;
    }

    /**
     * Remove subCategory
     *
     * @param \AppBundle\Entity\Category $subCategory
     */
    public function removeSubCategory(\AppBundle\Entity\Category $subCategory)
    {
        $this->subCategories->removeElement($subCategory);
    }

    /**
     * Get subCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubCategories()
    {
        return $this->subCategories;
    }

    function __toString()
    {
        return $this->name;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Category
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Category
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return mixed
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param mixed $imageFile
     */
    public function setImageFile(File $imageFile = null)
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return mixed
     */
    public function getImageSiteFile()
    {
        return $this->imageSiteFile;
    }

    /**
     * @param mixed $imageSiteFile
     */
    public function setImageSiteFile(File $imageSiteFile = null)
    {
        $this->imageSiteFile = $imageSiteFile;
        if ($imageSiteFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }


    /**
     * Add parent
     *
     * @param \AppBundle\Entity\Category $parent
     *
     * @return Category
     */
    public function addParent(\AppBundle\Entity\Category $parent)
    {
        $this->parents[] = $parent;

        return $this;
    }

    /**
     * Remove parent
     *
     * @param \AppBundle\Entity\Category $parent
     */
    public function removeParent(\AppBundle\Entity\Category $parent)
    {
        $this->parents->removeElement($parent);
    }

    /**
     * Get parents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * Add favoritesProduct
     *
     * @param \AppBundle\Entity\Product $favoritesProduct
     *
     * @return Category
     */
    public function addFavoritesProduct(\AppBundle\Entity\Product $favoritesProduct)
    {
        $this->favoritesProducts[] = $favoritesProduct;

        return $this;
    }

    /**
     * Remove favoritesProduct
     *
     * @param \AppBundle\Entity\Product $favoritesProduct
     */
    public function removeFavoritesProduct(\AppBundle\Entity\Product $favoritesProduct)
    {
        $this->favoritesProducts->removeElement($favoritesProduct);
    }

    /**
     * Get favoritesProducts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavoritesProducts()
    {
        return $this->favoritesProducts;
    }

    /**
     * Set imageSite
     *
     * @param string $imageSite
     *
     * @return Category
     */
    public function setImageSite($imageSite)
    {
        $this->imageSite = $imageSite;

        return $this;
    }

    /**
     * Get imageSite
     *
     * @return string
     */
    public function getImageSite()
    {
        return $this->imageSite;
    }

    /**
     * Add product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Category
     */
    public function addProduct(\AppBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \AppBundle\Entity\Product $product
     */
    public function removeProduct(\AppBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
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

    /**
     * Set isBrand
     *
     * @param boolean $isBrand
     *
     * @return Category
     */
    public function setIsBrand($isBrand)
    {
        $this->isBrand = $isBrand;

        return $this;
    }

    /**
     * Get isBrand
     *
     * @return boolean
     */
    public function getIsBrand()
    {
        return $this->isBrand;
    }

    /**
     * Set brandDescription
     *
     * @param string $brandDescription
     *
     * @return Category
     */
    public function setBrandDescription($brandDescription)
    {
        $this->brandDescription = $brandDescription;

        return $this;
    }

    /**
     * Get brandDescription
     *
     * @return string
     */
    public function getBrandDescription()
    {
        return $this->brandDescription;
    }
}
