<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Services\ProductService;

class ShopCartService
{
    private $entityManager;
    private $productRepository;
    private $shopCartProductRepository;
    private $productService;

    public function __construct(EntityManager $entityManager, ProductService $productService)
    {
      $this->entityManager = $entityManager;
      $this->productRepository = $entityManager->getRepository('AppBundle:Product');
      $this->shopCartProductRepository = $entityManager->getRepository('AppBundle:ShopCartProduct');
      $this->productService = $productService;
    }

    public function emptyShopCart($user)
    {
      $shopCartProducts = $this->shopCartProductRepository->findBy([
        'user' => $user->getId(),
      ]);
      foreach ($shopCartProducts as $shopCartProduct) {
        $this->entityManager->remove($shopCartProduct);
      }
      $this->entityManager->flush();
    }

    public function emptyShopCartBags($user)
    {
      $shopCartBags = $this->entityManager->getRepository('AppBundle:ShopCartBags')->findOneBy([
        'user' => $user->getId(),
      ]);
      if ($shopCartBags != null) {
        $this->entityManager->remove($shopCartBags);
        $this->entityManager->flush();
      }
    }

    public function getShopCartProducts($user)
    {
      if (!$user) {
        return [];
      }

      $shopCartProducts = $this->shopCartProductRepository->findBy([
        'user' => $user->getId(),
      ]);

      $productsDB = [];
      $categories = [];
      foreach ($shopCartProducts as $shopCartProduct) {
        if (
            $shopCartProduct->getProductId() == 'target15' ||
            $shopCartProduct->getProductId() == 'target25' ||
            $shopCartProduct->getProductId() == 'target50' ||
            $shopCartProduct->getProductId() == 'target100') {
          $name = 'Tarjeta de 15 CUC';
          switch ($shopCartProduct->getProductId()) {
            case 'target15':
              $price = 15;
              break;
            case 'target25':
              $name = 'Tarjeta de 25 CUC';
              $price = 25;
              break;
            case 'target50':
              $name = 'Tarjeta de 50 CUC';
              $price = 50;
              break;
            default:
              $name = 'Tarjeta de 100 CUC';
              $price = 100;
              break;
          }
          $productsDB[] = [
            'id' => $shopCartProduct->getProductId(),
            'uuid' => $shopCartProduct->getUuid(),
            'type' => 'target',
            'price' => $price,
            'count' => $shopCartProduct->getCount(),
            'name' => $name,
          ];
        } else {
          $productDB = $this->productRepository->find($shopCartProduct->getProductId());
          if ($productDB) {
            $price = $productDB->getPrice();

            $offerExists = false;
            $offerPrice = $this->productService->findProductOfferPrice($productDB);
            if ($offerPrice != -1) {
              $price = $offerPrice;
              $offerExists = true;
            }

            $productsDB[] = [
              'id' => $productDB->getId(),
              'uuid' => $shopCartProduct->getUuid(),
              'price' => $price,
              'offerExists' => $offerExists,
              'count' => $shopCartProduct->getCount(),
              'storeCount' => $productDB->getStoreCount(),
              'name' => $productDB->getName(),
              'image' => $productDB->getMainImage(),
              'weight' => $productDB->getWeight(),
              'ikeaPrice' => $productDB->getIkeaPrice(),
              'isFurniture' => $productDB->getIsFurniture(),
              'isFragile' => $productDB->getIsFragile(),
              'isAirplaneFurniture' => $productDB->getIsAriplaneForniture(),
              'isOversize' => $productDB->getIsOversize(),
              'isTableware' => $productDB->getIsTableware(),
              'isLamp' => $productDB->getIsLamp(),
              'numberOfPackages' => $productDB->getNumberOfPackages(),
              'isMattress' => $productDB->getIsMattress(),
              'isAirplaneMattress' => $productDB->getIsAriplaneMattress(),
              'isFaucet' => $productDB->getIsFaucet(),
              'isGrill' => $productDB->getIsGrill(),
              'isShelf' => $productDB->getIsShelf(),
              'isDesk' => $productDB->getIsDesk(),
              'isBookcase' => $productDB->getIsBookcase(),
              'isComoda' => $productDB->getIsComoda(),
              'isRepisa' => $productDB->getIsRepisa(),
              'comboProducts' => $productDB->getComboProducts(),
              'categories' => json_encode($categories),
            ];
          }
        }
      }
      return $productsDB;
    }

    public function getShopCartBags($user)
    {
      if ($user == null) {
        return null;
      }

      return $this->entityManager->getRepository('AppBundle:ShopCartBags')->findOneBy([
        'user' => $user->getId(),
      ]);
    }

    public function countShopCart($user)
    {
      if (!$user) {
        return 0;
      }

      $shopCartProducts = $this->shopCartProductRepository->findBy([
        'user' => $user->getId(),
      ]);

      $total = 0;
      foreach ($shopCartProducts as $shopCartProduct) {
        $total += $shopCartProduct->getCount();
      }

      return $total;
    }
}
