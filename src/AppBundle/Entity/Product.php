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
     * @ORM\Column(type="decimal")
     */
    private $price;
    /**
     * @ORM\Column(type="boolean")
     */
    private $popular;
    /**
     * @ORM\Column(type="integer")
     */
    private $priority;
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
    /**
     * @ORM\Column(type="boolean")
     */
    private $isMattress;

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
    private $isAriplaneMattress;
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
     * @ORM\Column(type="boolean")
     */
    private $isFaucet;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isGrill;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isShelf;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isDesk;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isBookcase;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isComoda;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isRepisa;
    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfPackages;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Evaluation", mappedBy="product")
     */
    private $evaluations;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ComboProduct", mappedBy="parentProduct")
     */
    private $comboProducts;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ComplementaryProduct", mappedBy="parentProduct")
     */
    private $complementaryProducts;
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Product")
     */
    private $similarProducts;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDisabled;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductMetaname", mappedBy="product")
     */
    private $metaNames;
    /**
     * @ORM\Column(type="float")
     */
    private $widthLeft;
    /**
     * @ORM\Column(type="float")
     */
    private $widthRight;
    /**
     * @ORM\Column(type="float")
     */
    private $width;
    /**
     * @ORM\Column(type="float")
     */
    private $heightMin;
    /**
     * @ORM\Column(type="float")
     */
    private $heightMax;
    /**
     * @ORM\Column(type="float")
     */
    private $height;
    /**
     * @ORM\Column(type="float")
     */
    private $deepMin;
    /**
     * @ORM\Column(type="float")
     */
    private $deepMax;
    /**
     * @ORM\Column(type="float")
     */
    private $deep;
    /**
     * @ORM\Column(type="float")
     */
    private $length;
    /**
     * @ORM\Column(type="float")
     */
    private $diameter;
    /**
     * @ORM\Column(type="float")
     */
    private $maxLoad;
    /**
     * @ORM\Column(type="float")
     */
    private $area;
    /**
     * @ORM\Column(type="float")
     */
    private $thickness;
    /**
     * @ORM\Column(type="float")
     */
    private $volume;
    /**
     * @ORM\Column(type="float")
     */
    private $surfaceDensity;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductCode", mappedBy="product")
     */
    private $codes;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductColor", mappedBy="product")
     */
    private $colors;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductMaterial", mappedBy="product")
     */
    private $materials;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinColumn(name="classification_id", referencedColumnName="id")
     */
    private $classification;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductRoom", mappedBy="product")
     */
    private $rooms;
    /**
     * @ORM\Column(type="string")
     */
    private $brand;

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
        $this->comboProducts = new ArrayCollection();
        $this->complementaryProducts = new ArrayCollection();
        $this->similarProducts = new ArrayCollection();
        $this->metaNames = new ArrayCollection();
        $this->codes = new ArrayCollection();
        $this->colors = new ArrayCollection();
        $this->materials = new ArrayCollection();
        $this->rooms = new ArrayCollection();
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

    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    public function getPriority()
    {
        return $this->priority;
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

    public function setIsMattress($isMattress)
    {
        $this->isMattress = $isMattress;

        return $this;
    }

    public function getIsMattress()
    {
        return $this->isMattress;
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

    public function setIsAriplaneMattress($isAriplaneMattress)
    {
        $this->isAriplaneMattress = $isAriplaneMattress;

        return $this;
    }

    public function getIsAriplaneMattress()
    {
        return $this->isAriplaneMattress;
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

    public function setIsFaucet($isFaucet)
    {
        $this->isFaucet = $isFaucet;

        return $this;
    }

    public function getIsFaucet()
    {
        return $this->isFaucet;
    }

    public function setIsGrill($isGrill)
    {
        $this->isGrill = $isGrill;

        return $this;
    }

    public function getIsGrill()
    {
        return $this->isGrill;
    }

    public function setIsShelf($isShelf)
    {
        $this->isShelf = $isShelf;

        return $this;
    }

    public function getIsShelf()
    {
        return $this->isShelf;
    }

    public function setIsDesk($isDesk)
    {
        $this->isDesk = $isDesk;

        return $this;
    }

    public function getIsDesk()
    {
        return $this->isDesk;
    }

    public function setIsBookcase($isBookcase)
    {
        $this->isBookcase = $isBookcase;

        return $this;
    }

    public function getIsBookcase()
    {
        return $this->isBookcase;
    }

    public function setIsComoda($isComoda)
    {
        $this->isComoda = $isComoda;

        return $this;
    }

    public function getIsComoda()
    {
        return $this->isComoda;
    }

    public function setIsRepisa($isRepisa)
    {
        $this->isRepisa = $isRepisa;

        return $this;
    }

    public function getIsRepisa()
    {
        return $this->isRepisa;
    }

    public function setNumberOfPackages($numberOfPackages)
    {
        $this->numberOfPackages = $numberOfPackages;

        return $this;
    }

    public function getNumberOfPackages()
    {
        return $this->numberOfPackages;
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

    public function addComboProduct(ComboProduct $comboProduct)
    {
        $this->comboProducts[] = $comboProduct;

        return $this;
    }

    public function removeComboProduct(ComboProduct $comboProduct)
    {
        $this->comboProducts->removeElement($comboProduct);
    }

    public function getComboProducts()
    {
        return $this->comboProducts;
    }

    public function addComplementaryProduct(ComplementaryProduct $complementaryProduct)
    {
        $this->complementaryProducts[] = $complementaryProduct;

        return $this;
    }

    public function removeComplementaryProduct(ComplementaryProduct $complementaryProduct)
    {
        $this->complementaryProducts->removeElement($complementaryProduct);
    }

    public function getComplementaryProducts()
    {
        return $this->complementaryProducts;
    }


    public function getLabels()
    {
      $labels = "";

      if ($this->isOversize) {
        $labels = $labels."Bulto independiente-";
      }
      if ($this->isDesk) {
        $labels = $labels."Buró-";
      }
      if ($this->isMattress) {
        $labels = $labels."Colchón-";
      }
      if ($this->isComoda) {
        $labels = $labels."Cómoda o Gavetero-";
      }
      if ($this->isAriplaneMattress) {
        $labels = $labels."Colchón por avión-";
      }
      if ($this->isShelf) {
        $labels = $labels."Estante-";
      }
      if ($this->isFragile) {
        $labels = $labels."Frágil-";
      }
      if ($this->isFaucet) {
        $labels = $labels."Grifo-";
      }
      if ($this->isGrill) {
        $labels = $labels."Grill-";
      }
      if ($this->isTableware) {
        $labels = $labels."Juego de vajilla-";
      }
      if ($this->isLamp) {
        $labels = $labels."Lámpara-";
      }
      if ($this->isBookcase) {
        $labels = $labels."Librero-";
      }
      if ($this->isFurniture) {
        $labels = $labels."Mobiliario-";
      }
      if ($this->isAriplaneForniture) {
        $labels = $labels."Mobiliario por avión-";
      }
      if ($this->isRepisa) {
        $labels = $labels."Repisa-";
      }

      return $labels;
    }

    public function addSimilarProduct(Product $similarProduct)
    {
        $this->similarProducts[] = $similarProduct;

        return $this;
    }

    public function removeSimilarProduct(Product $similarProduct)
    {
        $this->similarProducts->removeElement($similarProduct);
    }

    public function getSimilarProducts()
    {
        return $this->similarProducts;
    }

    public function setSimilarProducts($similarProducts)
    {
        return $this->similarProducts = $similarProducts;
    }

    public function getSimilarProductsCount()
    {
        return count($this->similarProducts);
    }

    public function setIsDisabled($isDisabled)
    {
        $this->isDisabled = $isDisabled;

        return $this;
    }

    public function getIsDisabled()
    {
        return $this->isDisabled;
    }

    public function addMetaName(ProductMetaName $metaName)
    {
        $this->metaNames[] = $metaName;

        return $this;
    }

    public function removeMetaName(ProductMetaName $metaName)
    {
        $this->metaNames->removeElement($metaName);
    }

    public function getMetaNames()
    {
        return $this->metaNames;
    }

    public function setWidthLeft($widthLeft)
    {
        $this->widthLeft = $widthLeft;

        return $this;
    }

    public function getWidthLeft()
    {
        return $this->widthLeft;
    }

    public function setWidthRight($widthRight)
    {
        $this->widthRight = $widthRight;

        return $this;
    }

    public function getWidthRight()
    {
        return $this->widthRight;
    }

    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setHeightMin($heightMin)
    {
        $this->heightMin = $heightMin;

        return $this;
    }

    public function getHeightMin()
    {
        return $this->heightMin;
    }

    public function setHeightMax($heightMax)
    {
        $this->heightMax = $heightMax;

        return $this;
    }

    public function getHeightMax()
    {
        return $this->heightMax;
    }

    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setDeepMin($deepMin)
    {
        $this->deepMin = $deepMin;

        return $this;
    }

    public function getDeepMin()
    {
        return $this->deepMin;
    }

    public function setDeepMax($deepMax)
    {
        $this->deepMax = $deepMax;

        return $this;
    }

    public function getDeepMax()
    {
        return $this->deepMax;
    }

    public function setDeep($deep)
    {
        $this->deep = $deep;

        return $this;
    }

    public function getDeep()
    {
        return $this->deep;
    }

    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function setDiameter($diameter)
    {
        $this->diameter = $diameter;

        return $this;
    }

    public function getDiameter()
    {
        return $this->diameter;
    }

    public function setMaxLoad($maxLoad)
    {
        $this->maxLoad = $maxLoad;

        return $this;
    }

    public function getMaxLoad()
    {
        return $this->maxLoad;
    }

    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    public function getArea()
    {
        return $this->area;
    }

    public function setThickness($thickness)
    {
        $this->thickness = $thickness;

        return $this;
    }

    public function getThickness()
    {
        return $this->thickness;
    }

    public function setVolume($volume)
    {
        $this->volume = $volume;

        return $this;
    }

    public function getVolume()
    {
        return $this->volume;
    }

    public function setSurfaceDensity($surfaceDensity)
    {
        $this->surfaceDensity = $surfaceDensity;

        return $this;
    }

    public function getSurfaceDensity()
    {
        return $this->surfaceDensity;
    }

    public function addCode(ProductCode $code)
    {
        $this->codes[] = $code;

        return $this;
    }

    public function removeCode(ProductCode $code)
    {
        $this->codes->removeElement($code);
    }

    public function getCodes()
    {
        return $this->codes;
    }

    public function addColor(ProductColor $color)
    {
        $this->colors[] = $color;

        return $this;
    }

    public function removeColor(ProductColor $color)
    {
        $this->colors->removeElement($color);
    }

    public function getColors()
    {
        return $this->colors;
    }

    public function addMaterial(ProductMaterial $material)
    {
        $this->materials[] = $material;

        return $this;
    }

    public function removeMaterial(ProductMaterial $material)
    {
        $this->materials->removeElement($material);
    }

    public function getMaterials()
    {
        return $this->materials;
    }

    public function setClassification(Category $classification = null)
    {
        $this->classification = $classification;

        return $this;
    }

    public function getClassification()
    {
        return $this->classification;
    }

    public function addRoom(ProductRoom $room)
    {
        $this->rooms[] = $room;

        return $this;
    }

    public function removeRoom(ProductRoom $room)
    {
        $this->rooms->removeElement($room);
    }

    public function getRooms()
    {
        return $this->rooms;
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    public function getBrand()
    {
        return $this->brand;
    }
}
