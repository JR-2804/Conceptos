<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class Product
 */
class Product
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
    private $item;
    /**
     * @ORM\Column(type="text")
     */
    private $description;
    /**
     * @ORM\Column(type="string")
     */
    private $code;
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Category", inversedBy="products")
     * @ORM\JoinTable(name="product_category")
     */
    private $categories;
    /**
     * @ORM\Column(type="text")
     */
    private $categoryText;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    private $mainImage;
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Image")
     * @ORM\JoinTable(name="product_images", joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", unique=true)})
     */
    private $images;
    /**
     * @ORM\Column(type="integer")
     */
    private $price;
    /**
     * @ORM\Column(type="boolean")
     */
    private $popular;
    /**
     * @ORM\Column(type="boolean")
     */
    private $recent;
    /**
     * @ORM\Column(type="boolean")
     */
    private $inStore;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $storeCount;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Color", inversedBy="products")
     * @ORM\JoinColumn(name="color_id", referencedColumnName="id")
     */
    private $color;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Material", inversedBy="products")
     * @ORM\JoinColumn(name="material_id", referencedColumnName="id")
     */
    private $material;
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Category", mappedBy="favoritesProducts")
     * @ORM\JoinTable(name="favorite_category", joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")})
     */
    private $favoritesCategory;
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Offer", mappedBy="products")
     * @ORM\JoinTable(name="product_offer", joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="offer_id", referencedColumnName="id")})
     */
    private $offers;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $parentCategoryText;
    /**
     * @ORM\Column(type="float")
     */
    private $weight;
    /**
     * @ORM\Column(type="float")
     */
    private $ikeaPrice;
    /**
     * @ORM\Column(type="float")
     */
    private $shipping;
    /**
     * @ORM\Column(type="float")
     */
    private $tax;
    /**
     * @ORM\Column(type="integer")
     */
    private $shippingLimit;
    /**
     * @ORM\Column(type="float")
     */
    private $calculatePrice;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FavoriteProduct", mappedBy="product")
     */
    private $userFavorites;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFurniture;

    private $priceOffer;

    private $favorite;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isHighlight;
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Image")
     * @ORM\JoinTable(name="product_highlight_images", joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", unique=true)})
     */
    private $highlightImages;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isFragile;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isAriplaneForniture;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isOversize;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isTableware;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isLamp;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Evaluation", mappedBy="product")
     */
    private $evaluations;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->favoritesCategory = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->priceOffer = -1;
        $this->categories = new ArrayCollection();
        $this->userFavorites = new ArrayCollection();
        $this->favorite = false;
        $this->highlightImages = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->code;
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

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setMainImage(Image $mainImage = null)
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    public function getMainImage()
    {
        return $this->mainImage;
    }

    public function addImage(Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
    }

    public function getImages()
    {
        return $this->images;
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

    public function setPopular($popular)
    {
        $this->popular = $popular;

        return $this;
    }

    public function getPopular()
    {
        return $this->popular;
    }

    public function setRecent($recent)
    {
        $this->recent = $recent;

        return $this;
    }

    public function getRecent()
    {
        return $this->recent;
    }

    public function setCategoryText($categoryText)
    {
        $this->categoryText = $categoryText;

        return $this;
    }

    public function getCategoryText()
    {
        return $this->categoryText;
    }

    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function setColor(Color $color = null)
    {
        $this->color = $color;

        return $this;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setMaterial(Material $material = null)
    {
        $this->material = $material;

        return $this;
    }

    public function getMaterial()
    {
        return $this->material;
    }

    public function addFavoritesCategory(Category $favoritesCategory)
    {
        $this->favoritesCategory[] = $favoritesCategory;

        return $this;
    }

    public function removeFavoritesCategory(Category $favoritesCategory)
    {
        $this->favoritesCategory->removeElement($favoritesCategory);
    }

    public function getFavoritesCategory()
    {
        return $this->favoritesCategory;
    }

    public function addOffer(Offer $offer)
    {
        $this->offers[] = $offer;

        return $this;
    }

    public function removeOffer(Offer $offer)
    {
        $this->offers->removeElement($offer);
    }

    public function getOffers()
    {
        return $this->offers;
    }

    public function setParentCategoryText($parentCategoryText)
    {
        $this->parentCategoryText = $parentCategoryText;

        return $this;
    }

    public function getParentCategoryText()
    {
        return $this->parentCategoryText;
    }

    public function setInStore($inStore)
    {
        $this->inStore = $inStore;

        return $this;
    }

    public function getInStore()
    {
        return $this->inStore;
    }

    public function setStoreCount($storeCount)
    {
        $this->storeCount = $storeCount;

        return $this;
    }

    public function getStoreCount()
    {
        return $this->storeCount;
    }

    public function getPriceOffer()
    {
        return $this->priceOffer;
    }

    public function setPriceOffer($priceOffer)
    {
        $this->priceOffer = $priceOffer;
    }

    public function addCategory(Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setIkeaPrice($ikeaPrice)
    {
        $this->ikeaPrice = $ikeaPrice;

        return $this;
    }

    public function getIkeaPrice()
    {
        return $this->ikeaPrice;
    }

    public function setShipping($shipping)
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function getShipping()
    {
        return $this->shipping;
    }

    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    public function getTax()
    {
        return $this->tax;
    }

    public function setCalculatePrice($calculatePrice)
    {
        $this->calculatePrice = $calculatePrice;

        return $this;
    }

    public function getCalculatePrice()
    {
        return $this->calculatePrice;
    }

    public function setShippingLimit($shippingLimit)
    {
        $this->shippingLimit = $shippingLimit;

        return $this;
    }

    public function getShippingLimit()
    {
        return $this->shippingLimit;
    }

    public function addUserFavorite(FavoriteProduct $userFavorite)
    {
        $this->userFavorites[] = $userFavorite;

        return $this;
    }

    public function removeUserFavorite(FavoriteProduct $userFavorite)
    {
        $this->userFavorites->removeElement($userFavorite);
    }

    public function getUserFavorites()
    {
        return $this->userFavorites;
    }

    public function getFavorite()
    {
        return $this->favorite;
    }

    public function setFavorite($favorite)
    {
        $this->favorite = $favorite;
    }

    public function setIsFurniture($isFurniture)
    {
        $this->isFurniture = $isFurniture;

        return $this;
    }

    public function getIsFurniture()
    {
        return $this->isFurniture;
    }

    public function setIsHighlight($isHighlight)
    {
        $this->isHighlight = $isHighlight;

        return $this;
    }

    public function getIsHighlight()
    {
        return $this->isHighlight;
    }

    public function addHighlightImage(Image $highlightImage)
    {
        $this->highlightImages[] = $highlightImage;

        return $this;
    }

    public function removeHighlightImage(Image $highlightImage)
    {
        $this->highlightImage->removeElement($highlightImage);
    }

    public function getHighlightImages()
    {
        return $this->highlightImages;
    }

    public function setIsFragile($isFragile)
    {
        $this->isFragile = $isFragile;

        return $this;
    }

    public function getIsFragile()
    {
        return $this->isFragile;
    }

    public function setIsAriplaneForniture($isAriplaneForniture)
    {
        $this->isAriplaneForniture = $isAriplaneForniture;

        return $this;
    }

    public function getIsAriplaneForniture()
    {
        return $this->isAriplaneForniture;
    }

    public function setIsOversize($isOversize)
    {
        $this->isOversize = $isOversize;

        return $this;
    }

    public function getIsOversize()
    {
        return $this->isOversize;
    }

    public function setIsTableware($isTableware)
    {
        $this->isTableware = $isTableware;

        return $this;
    }

    public function getIsTableware()
    {
        return $this->isTableware;
    }

    public function setIsLamp($isLamp)
    {
        $this->isLamp = $isLamp;

        return $this;
    }

    public function getIsLamp()
    {
        return $this->isLamp;
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
}
