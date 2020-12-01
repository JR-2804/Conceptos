<?php

namespace AppBundle\DTO;

class ProductDTO
{
    private $id;
    private $name;
    private $item;
    private $description;
    private $code;
    private $image;
    private $images;
    private $imagesToDelete;
    private $category;
    private $price;
    private $popular;
    private $priority;
    private $recent;
    private $inStore;
    private $countStore;
    private $color;
    private $material;
    private $favoritesCategories;
    private $comboProduct;
    private $isParent;
    private $similarProducts;
    private $complementaryProducts;
    private $weight;
    private $shippingLimit;
    private $ikeaPrice;
    private $calculatePrice;
    private $isFurniture;
    private $isMattress;
    private $isHighlight;
    private $highlightImages;
    private $isFragile;
    private $isAriplaneForniture;
    private $isAriplaneMattress;
    private $isOversize;
    private $isTableware;
    private $isLamp;
    private $isFaucet;
    private $isGrill;
    private $isShelf;
    private $isDesk;
    private $isBookcase;
    private $isComoda;
    private $isRepisa;
    private $numberOfPackages;
    private $isDisabled;
    private $metaNames;
    private $widthLeft;
    private $widthRight;
    private $width;
    private $heightMin;
    private $heightMax;
    private $height;
    private $deepMin;
    private $deepMax;
    private $deep;
    private $length;
    private $diameter;
    private $maxLoad;
    private $area;
    private $thickness;
    private $volume;
    private $surfaceDensity;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setImages($images)
    {
        $this->images = $images;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPopular()
    {
        return $this->popular;
    }

    public function setPopular($popular)
    {
        $this->popular = $popular;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    public function getRecent()
    {
        return $this->recent;
    }

    public function setRecent($recent)
    {
        $this->recent = $recent;
    }

    public function getImagesToDelete()
    {
        return $this->imagesToDelete;
    }

    public function setImagesToDelete($imagesToDelete)
    {
        $this->imagesToDelete = $imagesToDelete;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function setItem($item)
    {
        $this->item = $item;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }

    public function getMaterial()
    {
        return $this->material;
    }

    public function setMaterial($material)
    {
        $this->material = $material;
    }

    public function getFavoritesCategories()
    {
        return $this->favoritesCategories;
    }

    public function setFavoritesCategories($favoritesCategories)
    {
        $this->favoritesCategories = $favoritesCategories;
    }

    public function getComboProducts()
    {
        return $this->comboProduct;
    }

    public function setComboProducts($comboProduct)
    {
        $this->comboProduct = $comboProduct;
    }

    public function getIsParent(){
        return $this->isParent;
    }

    public function setIsParent($isParent)
    {
        $this->isParent = $isParent;
    }

    public function getSimilarProducts()
    {
        return $this->similarProducts;
    }

    public function setSimilarProducts($similarProducts)
    {
        $this->similarProducts = $similarProducts;

    }

    public function getComplementaryProducts()
    {
        return $this->complementaryProducts;
    }

    public function setComplementaryProducts($complementaryProducts)
    {
        $this->complementaryProducts = $complementaryProducts;
    }

    public function getInStore()
    {
        return $this->inStore;
    }

    public function setInStore($inStore)
    {
        $this->inStore = $inStore;
    }

    public function getCountStore()
    {
        return $this->countStore;
    }

    public function setCountStore($countStore)
    {
        $this->countStore = $countStore;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    public function getShippingLimit()
    {
        return $this->shippingLimit;
    }

    public function setShippingLimit($shippingLimit)
    {
        $this->shippingLimit = $shippingLimit;
    }

    public function getIkeaPrice()
    {
        return $this->ikeaPrice;
    }

    public function setIkeaPrice($ikeaPrice)
    {
        $this->ikeaPrice = $ikeaPrice;
    }

    public function getCalculatePrice()
    {
        return $this->calculatePrice;
    }

    public function setCalculatePrice($calculatePrice)
    {
        $this->calculatePrice = $calculatePrice;
    }

    public function getisFurniture()
    {
        return $this->isFurniture;
    }

    public function setIsFurniture($isFurniture)
    {
        $this->isFurniture = $isFurniture;
    }

    public function getIsMattress()
    {
        return $this->isMattress;
    }

    public function setIsMattress($isMattress)
    {
        $this->isMattress = $isMattress;
    }

    public function getIsHighlight()
    {
        return $this->isHighlight;
    }

    public function setIsHighlight($isHighlight)
    {
        $this->isHighlight = $isHighlight;
    }

    public function getHighlightImages()
    {
        return $this->highlightImages;
    }

    public function setHighlightImages($highlightImages)
    {
        $this->highlightImages = $highlightImages;
    }

    public function setIsFragile($isFragile)
    {
        $this->isFragile = $isFragile;
    }

    public function getIsFragile()
    {
        return $this->isFragile;
    }

    public function setIsAriplaneForniture($isAriplaneForniture)
    {
        $this->isAriplaneForniture = $isAriplaneForniture;
    }

    public function getIsAriplaneForniture()
    {
        return $this->isAriplaneForniture;
    }

    public function setIsAriplaneMattress($isAriplaneMattress)
    {
        $this->isAriplaneMattress = $isAriplaneMattress;
    }

    public function getIsAriplaneMattress()
    {
        return $this->isAriplaneMattress;
    }

    public function setIsOversize($isOversize)
    {
        $this->isOversize = $isOversize;
    }

    public function getIsOversize()
    {
        return $this->isOversize;
    }

    public function setIsTableware($isTableware)
    {
        $this->isTableware = $isTableware;
    }

    public function getIsTableware()
    {
        return $this->isTableware;
    }

    public function setIsLamp($isLamp)
    {
        $this->isLamp = $isLamp;
    }

    public function getIsLamp()
    {
        return $this->isLamp;
    }

    public function setIsFaucet($isFaucet)
    {
        $this->isFaucet = $isFaucet;
    }

    public function getIsFaucet()
    {
        return $this->isFaucet;
    }

    public function setIsGrill($isGrill)
    {
        $this->isGrill = $isGrill;
    }

    public function getIsGrill()
    {
        return $this->isGrill;
    }

    public function setIsShelf($isShelf)
    {
        $this->isShelf = $isShelf;
    }

    public function getIsShelf()
    {
        return $this->isShelf;
    }

    public function setIsDesk($isDesk)
    {
        $this->isDesk = $isDesk;
    }

    public function getIsDesk()
    {
        return $this->isDesk;
    }

    public function setIsBookcase($isBookcase)
    {
        $this->isBookcase = $isBookcase;
    }

    public function getIsBookcase()
    {
        return $this->isBookcase;
    }

    public function setIsComoda($isComoda)
    {
        $this->isComoda = $isComoda;
    }

    public function getIsComoda()
    {
        return $this->isComoda;
    }

    public function setIsRepisa($isRepisa)
    {
        $this->isRepisa = $isRepisa;
    }

    public function getIsRepisa()
    {
        return $this->isRepisa;
    }

    public function setNumberOfPackages($numberOfPackages)
    {
        $this->numberOfPackages = $numberOfPackages;
    }

    public function getNumberOfPackages()
    {
        return $this->numberOfPackages;
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

    public function getMetaNames()
    {
        return $this->metaNames;
    }

    public function setMetaNames($metaNames)
    {
        $this->metaNames = $metaNames;
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
}
