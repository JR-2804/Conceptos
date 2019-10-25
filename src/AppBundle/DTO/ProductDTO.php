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
    private $numberOfPackages;

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

    public function setNumberOfPackages($numberOfPackages)
    {
        $this->numberOfPackages = $numberOfPackages;
    }

    public function getNumberOfPackages()
    {
        return $this->numberOfPackages;
    }
}
