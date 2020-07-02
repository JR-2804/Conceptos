<?php

namespace AppBundle\Services;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ProductService
{
    private $em;
    private $uploaderHelper;
    private $categoryRepository;
    private $colorRepository;
    private $configurationRepository;
    private $favoriteProductRepository;
    private $materialRepository;
    private $memberRepository;
    private $offerRepository;
    private $pageRepository;
    private $productRepository;

    public function __construct(EntityManager $em, UploaderHelper $uploaderHelper)
    {
        $this->em = $em;
        $this->uploaderHelper = $uploaderHelper;
        $this->categoryRepository = $this->em->getRepository('AppBundle:Category');
        $this->colorRepository = $this->em->getRepository('AppBundle:Color');
        $this->configurationRepository = $this->em->getRepository('AppBundle:Configuration');
        $this->favoriteProductRepository = $this->em->getRepository('AppBundle:FavoriteProduct');
        $this->materialRepository = $this->em->getRepository('AppBundle:Material');
        $this->memberRepository = $this->em->getRepository('AppBundle:Member');
        $this->offerRepository = $this->em->getRepository('AppBundle:Offer');
        $this->pageRepository = $this->em->getRepository('AppBundle:Page\Page');
        $this->productRepository = $this->em->getRepository('AppBundle:Product');
    }

    public function find($id)
    {
        return $this->productRepository->find($id);
    }

    public function findProductOfferPrice($product)
    {
      $offers = $this->offerRepository->createQueryBuilder('o')
        ->join('o.products', 'p')
        ->where('p.id = :product AND o.startDate <= :date AND o.endDate >= :date')
        ->setParameter('product', $product->getId())
        ->setParameter('date', new \DateTime(), Type::DATE)
        ->getQuery()->getResult();

      if (count($offers) > 0) {
        return $offers[0]->getPrice();
      }

      foreach ($product->getCategories() as $category) {
        $offer = $category->getOffers()[0];
        if ($offer != null && (!$offer->getOnlyInStoreProducts() || ($offer->getOnlyInStoreProducts() && $product->getInStore()))) {
          return ceil($product->getPrice() * (1 - $offer->getPrice() / 100));
        } else {
          foreach ($category->getParents() as $parentCategory) {
            $offer = $parentCategory->getOffers()[0];
            if ($offer != null && (!$offer->getOnlyInStoreProducts() || ($offer->getOnlyInStoreProducts() && $product->getInStore()))) {
              return ceil($product->getPrice() * (1 - $offer->getPrice() / 100));
            }
          }
        }
    }

      return -1;
    }

    public function findAll()
    {
        return $this->productRepository->findAll();
    }

    public function updateCategoryReference()
    {
        $products = $this->findAll();
        foreach ($products as $product) {
            $product->addCategory($product->getCategory());
        }
        $this->em->flush();
    }

    public function existProductInFavorite($productId, $userId)
    {
        $productFavorite = $this->favoriteProductRepository->createQueryBuilder('fp')
            ->join('fp.user', 'u')
            ->join('fp.product', 'p')
            ->where('u.id = :user AND p.id = :product')
            ->setParameter('user', $userId)
            ->setParameter('product', $productId)
            ->getQuery()->getResult();
        if (count($productFavorite) > 0) {
            return true;
        }

        return false;
    }

    public function calculateProductPrice(
        $weight,
        $ikeaPrice,
        $isFurniture,
        $isFragile,
        $isAriplaneForniture,
        $isOversize,
        $isTableware,
        $isLamp,
        $numberOfPackages,
        $isMattress,
        $isAriplaneMattress,
        $isFaucet,
        $isGrill,
        $isShelf,
        $isDesk,
        $isBookcase,
        $isComoda,
        $isRepisa
    ) {
        if (($isFurniture && !$isAriplaneForniture) || ($isMattress && !$isAriplaneMattress)) {
          $numberOfPackagesExtra = ($numberOfPackages && $numberOfPackages > 1) ? $numberOfPackages * 10 : 10;
          $mattressExtra = $isMattress ? 20 : 0;
          $grillExtra = $isGrill ? 20 : 0;
          $shelfExtra = $isShelf ? 40 : 0;
          $deskExtra = $isDesk ? 20 : 0;
          $bookcaseExtra = $isBookcase ? 30 : 0;
          $comodaExtra = $isComoda ? 70 : 0;
          $repisaExtra = $isRepisa ? 20 : 0;

          return ceil(
            ($ikeaPrice*1.2 + $weight*5 + $numberOfPackagesExtra + $mattressExtra + $grillExtra + $shelfExtra + $deskExtra + $bookcaseExtra + $comodaExtra + $repisaExtra + 20)*1.8
          );
        } else {
          $fragileExtra = $isFragile ? 4 : 0;
          $lampExtra = $isLamp ? 20 : 0;
          $oversizeExtra = $isOversize ? 20 : 0;
          $mattressExtra = $isMattress ? 50 : 0;
          $tablewareExtra = $isTableware ? 60 : 0;
          $faucetExtra = $isFaucet ? 20 : 0;
          $grillExtra = $isGrill ? 50 : 0;
          $shelfExtra = $isShelf ? 70 : 0;
          $deskExtra = $isDesk ? 50 : 0;
          $bookcaseExtra = $isBookcase ? 60 : 0;
          $comodaExtra = $isComoda ? 100 : 0;
          $repisaExtra = $isRepisa ? 50 : 0;

          return ceil(
            ($ikeaPrice*1.2 + $weight*17.5 + $fragileExtra + $lampExtra + $oversizeExtra + $mattressExtra + $tablewareExtra + $faucetExtra + $grillExtra + $shelfExtra + $deskExtra + $bookcaseExtra + $comodaExtra + $repisaExtra)*1.8
          );
        }
    }

    public function recalculatePrices()
    {
        $batchSize = 20;
        $i = 0;
        $q = $this->em->createQuery('select p from AppBundle\Entity\Product p');
        $iterableResult = $q->iterate();
        foreach ($iterableResult as $row) {
            $product = $row[0];
            $finalPrice = $this->calculateProductPrice(
                $product->getWeight(),
                $product->getIkeaPrice(),
                $product->getIsFurniture(),
                $product->getIsFragile(),
                false,
                $product->getIsOversize(),
                $product->getIsTableware(),
                $product->getIsLamp(),
                $product->getNumberOfPackages(),
                $product->getIsMattress(),
                false,
                $product->getIsFaucet(),
                $product->getIsGrill(),
                $product->getIsShelf(),
                $product->getIsDesk(),
                $product->getIsBookcase(),
                $product->getIsComoda(),
                $product->getIsRepisa()
            );
            $product->setPrice($finalPrice);
            if (0 === ($i % $batchSize)) {
                $this->em->flush(); // Executes all updates.
                $this->em->clear(); // Detaches all objects from Doctrine!
            }
            ++$i;
        }
        $this->em->flush();
    }

    public function getPriceRange()
    {
        $priceRange = $this->productRepository
            ->createQueryBuilder('product')
            ->select('MIN(product.price) as minPrice, MAX(product.price) as maxPrice')
            ->getQuery()
            ->getSingleResult()
        ;

        return [
            'minPrice' => $priceRange['minPrice'],
            'maxPrice' => $priceRange['maxPrice'],
        ];
    }

    public function filterProducts($request, $user)
    {
        $products = [];
        $mainSubcategory = null;
        $mainCategory = null;

        $qbProduct = $this->productRepository->createQueryBuilder('p');
        $qbProductCount = $this->productRepository->createQueryBuilder('p')->select('count(p.id) as count_products');

        $populars = $request->query->get('populars', -1);
        $inStore = $request->query->get('inStore', -1);
        $categories = $request->query->get('categories', -1);
        $priceMin = $request->query->get('priceMin', -1);
        $priceMax = $request->query->get('priceMax', -1);
        $color = $request->query->get('color', -1);
        $material = $request->query->get('material', -1);
        $term = $request->query->get('term', '');
        $recent = $request->query->get('recent', -1);
        $inOffer = $request->query->get('inOffer', -1);
        $page = $request->query->get('page', 1);


        $hasWhere = false;
        if (-1 != $priceMin && '' != $priceMin) {
            $qbProduct->where('p.price >= :minPrice')->setParameter('minPrice', $priceMin);
            $qbProductCount->where('p.price >= :minPrice')->setParameter('minPrice', $priceMin);
            $hasWhere = true;
        }
        if (-1 != $priceMax && '' != $priceMax) {
            if ($hasWhere) {
                $qbProduct->andWhere('p.price <= :maxPrice');
                $qbProductCount->andWhere('p.price <= :maxPrice');
            } else {
                $qbProduct->where('p.price <= :maxPrice');
                $qbProductCount->where('p.price <= :maxPrice');
                $hasWhere = true;
            }
            $qbProduct->setParameter('maxPrice', $priceMax);
            $qbProductCount->setParameter('maxPrice', $priceMax);
        }
        if (-1 != $color) {
            $colorName = $this->colorRepository->find($color)->getName();

            $qbProduct->leftJoin('p.color', 'c');
            $qbProduct->leftJoin('p.comboProducts', 'cp');
            $qbProduct->leftJoin('cp.product', 'cpp');
            $qbProduct->leftJoin('cpp.color', 'cppc');
            $qbProductCount->leftJoin('p.color', 'c');
            $qbProductCount->leftJoin('p.comboProducts', 'cp');
            $qbProductCount->leftJoin('cp.product', 'cpp');
            $qbProductCount->leftJoin('cpp.color', 'cppc');
            if ($hasWhere) {
                $qbProduct->andWhere('(cp.id is null and c.name like :color) or (cppc.name like :color)');
                $qbProductCount->andWhere('(cp.id is null and c.name like :color) or (cppc.name like :color)');
            } else {
                $qbProduct->where('(cp.id is null and c.name like :color) or (cppc.name like :color)');
                $qbProductCount->where('(cp.id is null and c.name like :color) or (cppc.name like :color)');
                $hasWhere = true;
            }
            $qbProduct->setParameter('color', $colorName."%");
            $qbProductCount->setParameter('color', $colorName."%");
        }
        if (-1 != $material) {
            $qbProduct->join('p.material', 'm');
            $qbProduct->leftJoin('p.comboProducts', 'mp');
            $qbProduct->leftJoin('mp.product', 'mpp');
            $qbProduct->leftJoin('mpp.material', 'mppm');
            $qbProductCount->join('p.material', 'm');
            $qbProductCount->leftJoin('p.comboProducts', 'mp');
            $qbProductCount->leftJoin('mp.product', 'mpp');
            $qbProductCount->leftJoin('mpp.material', 'mppm');
            if ($hasWhere) {
                $qbProduct->andWhere('(mp.id is null and m.id = :material) or (mppm.id = :material)');
                $qbProductCount->andWhere('(mp.id is null and m.id = :material) or (mppm.id = :material)');
            } else {
                $qbProduct->where('(mp.id is null and m.id = :material) or (mppm.id = :material)');
                $qbProductCount->where('(mp.id is null and m.id = :material) or (mppm.id = :material)');
                $hasWhere = true;
            }
            $qbProduct->setParameter('material', $material);
            $qbProductCount->setParameter('material', $material);
        }
        if ('' != $term) {
            if ($hasWhere) {
                $qbProduct->andWhere('p.name LIKE :term OR p.description LIKE :term OR p.item LIKE :term OR p.code LIKE :term')
                    ->setParameter('term', '%'.$term.'%')
                ;
                $qbProductCount->andWhere('p.name LIKE :term OR p.description LIKE :term OR p.item LIKE :term OR p.code LIKE :term')
                    ->setParameter('term', '%'.$term.'%')
                ;
            } else {
                $qbProduct->where('p.name LIKE :term OR p.description LIKE :term OR p.item LIKE :term OR p.code LIKE :term')
                    ->setParameter('term', '%'.$term.'%')
                ;
                $qbProductCount->where('p.name LIKE :term OR p.description LIKE :term OR p.item LIKE :term OR p.code LIKE :term')
                    ->setParameter('term', '%'.$term.'%')
                ;
                $hasWhere = true;
            }
        }

        if (-1 != $categories && '' != $categories) {
            $categories = explode(',', $categories);
            $qbProduct->join('p.categories', 'ca');
            $qbProductCount->join('p.categories', 'ca');
            if ($hasWhere) {
                $qbProduct->andWhere('ca.id IN (:categories)');
                $qbProductCount->andWhere('ca.id IN (:categories)');
            } else {
                $qbProduct->where('ca.id IN (:categories)');
                $qbProductCount->where('ca.id IN (:categories)');
                $hasWhere = true;
            }
            $qbProduct->setParameter('categories', $categories);
            $qbProductCount->setParameter('categories', $categories);

            $count = 0;
            foreach ($categories as $c) {
                $count = $count + 1;
            }

            if ($count > 1) {
                $mainCategory = $this->categoryRepository->find($categories[0])->getParents()[0];
            } else {
                $mainSubcategory = $this->categoryRepository->find($categories[0]);
                $mainCategory = $mainSubcategory->getParents()[0];
            }
        }

        if (-1 != $populars) {
            if ($hasWhere) {
                $qbProduct->andWhere('p.popular = true');
                $qbProductCount->andWhere('p.popular = true');
            } else {
                $qbProduct->where('p.popular = true');
                $qbProductCount->where('p.popular = true');
                $hasWhere = true;
            }
        }
        if (-1 != $recent) {
            if ($hasWhere) {
                $qbProduct->andWhere('p.recent = true');
                $qbProductCount->andWhere('p.recent = true');
            } else {
                $qbProduct->where('p.recent = true');
                $qbProductCount->where('p.recent = true');
                $hasWhere = true;
            }
        }
        if (-1 != $inStore) {
            if ($hasWhere) {
                $qbProduct->andWhere('p.inStore = true');
                $qbProductCount->andWhere('p.inStore = true');
            } else {
                $qbProduct->where('p.inStore = true');
                $qbProductCount->where('p.inStore = true');
                $hasWhere = true;
            }
        }

        if (-1 != $inOffer) {
            $activeOfferQB = $this->offerRepository->createQueryBuilder('o');
            $activeOfferQB->where('o.startDate <= (:now)');
            $activeOfferQB->where('o.endDate >= (:now)');
            $activeOfferQB->setParameter('now', new \DateTime(), Type::DATE);
            $activeOffers = $activeOfferQB->getQuery()->getResult();

            foreach ($activeOffers as $activeOffer)
                foreach ($activeOffer->getCategories() as $_categories)
                    foreach ($_categories->getProducts() as $product)
                        array_push($products, $product);

            $qbProduct->leftJoin('p.offers', 'o');
            $qbProductCount->leftJoin('p.offers', 'o');
            if ($hasWhere) {
                $qbProduct->andWhere('(o.startDate <= :current AND o.endDate >= :current)');
                $qbProductCount->andWhere('(o.startDate <= :current AND o.endDate >= :current)');
            } else {
                $qbProduct->where('(o.startDate <= :current AND o.endDate >= :current)');
                $qbProductCount->where('(o.startDate <= :current AND o.endDate >= :current)');
            }
            $qbProduct->setParameter('current', new \DateTime(), Type::DATE);
            $qbProductCount->setParameter('current', new \DateTime(), Type::DATE);

        } else {
            $qbProduct->leftJoin('p.offers', 'o');
            $qbProductCount->leftJoin('p.offers', 'o');
        }

        $firstResult = 0;
        if (1 != $page) {
            $firstResult = (($page - 1) * 50);
        }
        
        $_products = $qbProduct
            ->orderBy('p.name', 'ASC')
            ->addOrderBy('o.price', 'DESC')
            ->addOrderBy('p.inStore', 'DESC')
            ->addOrderBy('p.price', 'ASC')
            ->setFirstResult($firstResult)
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();

        $similars = [];
        foreach ($_products as $product){
            foreach ($product->getSimilarProducts() as $similarProduct)
                $similars[] = $similarProduct->getId();

            if (!in_array($product->getId(), $similars))
                $products[] = $product;
        }


        $countProducts = $qbProductCount->getQuery()->getSingleScalarResult();

        foreach ($products as $product) {
            if ($user) {
                $product->setFavorite($this->existProductInFavorite($product->getId(), $user->getId()));
            }
            $offerPrice = $this->findProductOfferPrice($product);
            $product->setPriceOffer($offerPrice);
        }

        $inOfferHeader = false;
        $inStoreHeader = false;
        $recentHeader = false;
        $generalHeader = false;
        if (null == $mainCategory) {
            if (-1 != $inOffer) {
                $inOfferHeader = true;
            }
            elseif (-1 != $inStore) {
                $inStoreHeader = true;
            }
            elseif (-1 != $recent) {
                $recentHeader = true;
            }
            else {
                $generalHeader = true;
            }
        }




        $popularProducts = [];
        if ($categories != -1) {
            $popularProductsCount = 15;
            $categoriesPopularProducts = [];

            foreach ($categories as $category) {
                $categoryId = intval($category);
                $category = $this->categoryRepository->find($categoryId);
                if ($category == null)
                    continue;
                foreach ($category->getProducts() as $product)
                    if ($product->getPopular()) {
                        $categoriesPopularProducts[] = $product;
                    }
            }

            if (count($categoriesPopularProducts) >= 3) {
                $randomIndexes = array_rand($categoriesPopularProducts,
                    min(count($categoriesPopularProducts), $popularProductsCount));
                foreach ($randomIndexes as $randomIndex)
                    $popularProducts[] = $categoriesPopularProducts[$randomIndex];
            }
        }


        return [
            'products' => $products,
            'countProducts' => $countProducts,
            'popularProducts'=>$popularProducts,
            'mainCategory' => $mainCategory,
            'mainSubcategory' => $mainSubcategory,
            'inOfferHeader' => $inOfferHeader,
            'inStoreHeader' => $inStoreHeader,
            'recentHeader' => $recentHeader,
            'generalHeader' => $generalHeader,
        ];
    }
}
