<?php

namespace AppBundle\Controller;

use AppBundle\DTO\CheckOutDTO;
use AppBundle\DTO\EmailDTO;
use AppBundle\DTO\MembershipRequestDTO;
use AppBundle\Entity\Evaluation;
use AppBundle\Entity\FavoriteProduct;
use AppBundle\Entity\ShopCartProduct;
use AppBundle\Entity\ShopCartBags;
use AppBundle\Entity\Request\Client;
use AppBundle\Entity\Request\Request as ProductRequest;
use AppBundle\Entity\Request\Facture;
use AppBundle\Entity\Request\ExternalRequest;
use AppBundle\Entity\Request\ExternalRequestProduct;
use AppBundle\Entity\Request\PreFacture;
use AppBundle\Entity\Request\RequestCard;
use AppBundle\Entity\Request\FactureCard;
use AppBundle\Entity\Request\PreFactureCard;
use AppBundle\Entity\Request\RequestProduct;
use AppBundle\Entity\Request\FactureProduct;
use AppBundle\Entity\Request\PreFactureProduct;
use AppBundle\Form\CheckOutType;
use AppBundle\Form\MembershipRequestType;
use AppBundle\Form\EmailType;
use AppBundle\Repository\PromotionEmailRepository;
use Doctrine\DBAL\Types\Type;
use http\Message\Body;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class SiteController extends Controller
{
    /**
     * @Route(name="site_home", path="/")
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $onlySlide = $request->query->get('onlySlide');

        $offers = $this->getDoctrine()->getManager()->getRepository('AppBundle:Offer')
            ->createQueryBuilder('o')
            ->where('o.startDate <= :date AND o.endDate >= :date')
            ->setParameter('date', new \DateTime(), Type::DATE)
            ->getQuery()->getResult();

        $offersImage = [];
        foreach ($offers as $offer) {
            if (null != $offer->getImage()) {
                $offersImage[] = [
                    'image' => $this->get('vich_uploader.templating.helper.uploader_helper')->asset($offer, 'imageFile'),
                    'name' => $offer->getName(),
                    'description' => $offer->getDescription(),
                    'start' => date_format($offer->getStartDate(), 'd/m/Y'),
                    'end' => date_format($offer->getEndDate(), 'm/d/Y'),
                    'products' => $offer->getProducts(),
                ];
            }
            foreach ($offer->getProducts() as $product) {
                $offerPrice = $this->get('product_service')->findProductOfferPrice($product);
                $product->setPriceOffer($offerPrice);
                if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
                  $product->setFavorite($this->get('product_service')->existProductInFavorite($product->getId(), $this->getUser()->getId()));
                }
            }
        }

        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);
        $lasts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')->createQueryBuilder('p')
            ->orderBy('p.createdDate', 'DESC')
            ->setMaxResults(3)->getQuery()->getResult();
        $lasted = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->findBy([
            'recent' => true,
        ], null, 50);

        $inStore = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->findBy([
            'inStore' => true,
        ], null, 50);

        $rowsOfPopularProductsInDesktop = 2;
        $columnsOfPopularProductsInDesktop = 6;
        if (array_key_exists('popularProductsStyleDesktop', $page->getData())) {
          $popularProductsStyleDesktop = explode("x", $page->getData()["popularProductsStyleDesktop"]);
          $rowsOfPopularProductsInDesktop = $popularProductsStyleDesktop[0];
          $columnsOfPopularProductsInDesktop = $popularProductsStyleDesktop[1];
        }

        $rowsOfPopularProductsInMobile = 3;
        $columnsOfPopularProductsInMobile = 2;
        if (array_key_exists('popularProductsStyleMobile', $page->getData())) {
          $popularProductsStyleMobile = explode("x", $page->getData()["popularProductsStyleMobile"]);
          $rowsOfPopularProductsInMobile = $popularProductsStyleMobile[0];
          $columnsOfPopularProductsInMobile = $popularProductsStyleMobile[1];
        }

        $rowsOfStoreProductsInDesktop = 1;
        $columnsOfStoreProductsInDesktop = 4;
        if (array_key_exists('storeProductsStyleDesktop', $page->getData())) {
          $storeProductsStyleDesktop = explode("x", $page->getData()["storeProductsStyleDesktop"]);
          $rowsOfStoreProductsInDesktop = $storeProductsStyleDesktop[0];
          $columnsOfStoreProductsInDesktop = $storeProductsStyleDesktop[1];
        }

        $rowsOfStoreProductsInMobile = 1;
        $columnsOfStoreProductsInMobile = 1;
        if (array_key_exists('storeProductsStyleMobile', $page->getData())) {
          $storeProductsStyleMobile = explode("x", $page->getData()["storeProductsStyleMobile"]);
          $rowsOfStoreProductsInMobile = $storeProductsStyleMobile[0];
          $columnsOfStoreProductsInMobile = $storeProductsStyleMobile[1];
        }

        #TODO: poner el random del doctrine
        shuffle($inStore);
        $inStore = array_slice($inStore,  0,50);

        $populars = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')
          ->createQueryBuilder('product')
          ->where('product.popular = true')
          ->orderBy('product.priority', 'DESC')
          ->setMaxResults(150)
          ->getQuery()
          ->getResult();

        foreach ($populars as $product) {
          $offerPrice = $this->get('product_service')->findProductOfferPrice($product);
          $product->setPriceOffer($offerPrice);
          if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $product->setFavorite($this->get('product_service')->existProductInFavorite($product->getId(), $this->getUser()->getId()));
          }
        }

        $products = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->findAll();

        $inStoreHighlight = null;
        $lastedHighlight = null;
        foreach ($products as $product) {
          if ($product->getIsHighlight()) {
            $offerPrice = $this->get('product_service')->findProductOfferPrice($product);
            $product->setPriceOffer($offerPrice);
            if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
                $product->setFavorite($this->get('product_service')->existProductInFavorite($product->getId(), $this->getUser()->getId()));
            }

            if ($product->getInStore()) {
              $inStoreHighlight = $product;
            }
            elseif ($product->getRecent()) {
              $lastedHighlight = $product;
            }
          }
        }
        if (!$inStoreHighlight) {
          $inStoreHighlight = $inStore[count($inStore) - 1];
        }
        if (!$lastedHighlight and count($lasted) > 0) {
          $lastedHighlight = $lasted[count($lasted) - 1];
        }

        foreach ($inStore as $product) {
            $offerPrice = $this->get('product_service')->findProductOfferPrice($product);
            $product->setPriceOffer($offerPrice);
            if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
                $product->setFavorite($this->get('product_service')->existProductInFavorite($product->getId(), $this->getUser()->getId()));
            }
        }

        foreach ($lasted as $product) {
            $offerPrice = $this->get('product_service')->findProductOfferPrice($product);
            $product->setPriceOffer($offerPrice);
            if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
                $product->setFavorite($this->get('product_service')->existProductInFavorite($product->getId(), $this->getUser()->getId()));
            }
        }
        $brands = $this->getDoctrine()->getRepository('AppBundle:Category')->findBy([
            'isBrand' => true,
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        $showSuccessToast = false;
        if ($request->getSession()->get('successRequestToast') == true) {
          $request->getSession()->set('successRequestToast', false);
          $showSuccessToast = true;
        }

        $loginError = false;
        if ($request->getSession()->get('loginError') == true) {
          $request->getSession()->set('successRequestToast', false);
          $loginError = true;
        }

        return $this->render(':site:home.html.twig', [
            'onlySlide' => $onlySlide,
            'showSuccessToast' => $showSuccessToast,
            'loginError' => $loginError,
            'offers' => $offers,
            'offersImage' => $offersImage,
            'lasted' => $lasted,
            'lastedHighlight' => $lastedHighlight,
            'page' => $page,
            'membership' => $membership,
            'home' => $page,
            'lasts' => $lasts,
            'columnsOfPopularProductsInDesktop' => $columnsOfPopularProductsInDesktop,
            'columnsOfPopularProductsInMobile' => $columnsOfPopularProductsInMobile,
            'columnsOfStoreProductsInDesktop' => $columnsOfStoreProductsInDesktop,
            'columnsOfStoreProductsInMobile' => $columnsOfStoreProductsInMobile,
            'popularChunksDesktop' => array_chunk($populars, $rowsOfPopularProductsInDesktop * $columnsOfPopularProductsInDesktop),
            'popularChunks' => array_chunk($populars, $rowsOfPopularProductsInMobile * $columnsOfPopularProductsInMobile),
            'inStoreChunksDesktop' => array_chunk($inStore, $rowsOfStoreProductsInDesktop * $columnsOfStoreProductsInDesktop),
            'inStoreChunks' => array_chunk($inStore, $rowsOfStoreProductsInMobile * $columnsOfStoreProductsInMobile),
            'inStoreHighlight' => $inStoreHighlight,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'config' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1),
            'brands' => array_chunk($brands, 4),
            'currentDate' => new \DateTime(),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="home_slide", path="/home/slide")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function homeSlideAction(Request $request, $onlySlide)
    {
      $offers = $this->getDoctrine()->getManager()->getRepository('AppBundle:Offer')
        ->createQueryBuilder('o')
        ->where('o.startDate <= :date AND o.endDate >= :date')
        ->setParameter('date', new \DateTime(), Type::DATE)
        ->getQuery()->getResult();

      if ($onlySlide) {
        $slidePreview = $this->getDoctrine()->getManager()->getRepository('AppBundle:SlidePreview')->find(1);
        $page = $slidePreview;
      } else {
        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
          'name' => 'Home',
        ]);
      }

      return $this->render(':site:home-slide.html.twig', [
        'offers' => $offers,
        'page' => $page,
      ]);
    }

    /**
     * @Route(name="about_us", path="sobre-nosotros")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutUsAction(Request $request)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);
        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Sobre Nosotros',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render(':site:about_us.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'page' => $page,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="download_app", path="/app")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function appAction(Request $request)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);
        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'APP',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render(':site:app.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'page' => $page,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'config' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="products", path="/products")
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productsAction(Request $request)
    {
        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Productos',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        $filteredColors = [];
        $colors = $this->get('color_service')->getAll();
        foreach ($colors as $color) {
          $exists = false;
          foreach ($filteredColors as $filteredColor) {
            if (strtolower($filteredColor->getName()) == strtolower($color->getName())) {
              $exists = true;
            }
          }

          $multipleWords = explode(' ', $color->getName());
          $separated = explode('-', $color->getName());

          if (!$exists and count($multipleWords) == 1 and count($separated) == 1) {
            $filteredColors[] = $color;
          }
        }

        $result = [];
        $result += $this->get('page_service')->getHome();
        $result += $this->get('material_service')->getAll();
        $result += $this->get('product_service')->getPriceRange();
        $result += $this->get('product_service')->filterProducts($request, $this->getUser());
        $result += ['colors' => $filteredColors];
        $result += ['categories' => $this->get('category_service')->getAll()];
        $result += ['terms' => $config->getTermAndConditions()];
        $result += ['privacy' => $config->getPrivacyPolicy()];
        $result += ['currentDate' => new \DateTime()];
        $result += ['page' => $page];
        $result += ['membership' => $membership];
        $result += ['count' => $this->get('shop_cart_service')->countShopCart($this->getUser())];
        $result += ['shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser())];
        $result += ['shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser())];

        return $this->render(':site:products.html.twig', $result);
    }

    /**
     * @Route(name="product_details", path="/product/{id}")
     *
     * @param Request $request
     * @param $id
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailsAction(Request $request, $id)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        $product = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->find($id);
        if ($product == null) {
            #TODO: We should return a better 404 not found template and return the header code
            return $this->render(':site:product-details.html.twig', [
                'product' => null,
                'imageSets' => null,
                'home' => $home,
                'membership' => $membership,
                'currentDate' => new \DateTime(),
                'related' => null,
                'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
                'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
                'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
                'categories' => $this->get('category_service')->getAll(),
                'terms' => $config->getTermAndConditions(),
                'privacy' => $config->getPrivacyPolicy(),
            ]);
        }

        $offerPrice = $this->get('product_service')->findProductOfferPrice($product);
        $product->setPriceOffer($offerPrice);

        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $product->setFavorite($this->get('product_service')->existProductInFavorite($product->getId(), $this->getUser()->getId()));
        }

        $filterParameter = [$product->getId()];
        foreach ($product->getComboProducts() as $comboProduct) {
          $filterParameter[] = $comboProduct->getProduct()->getId();
        }

        $related = [];
        if (count($related) < 12) {
            $categories = [];
            foreach ($product->getCategories() as $category) {
                $categories[] = $category->getId();
            }
            $otherRelated = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->createQueryBuilder('p')
                ->join('p.categories', 'c')
                ->where('c.id IN (:category) AND p.id NOT IN (:current)')
                ->setParameter('category', $categories)
                ->setParameter('current', $filterParameter)
                ->orderBy('p.name', 'ASC')
                ->setMaxResults(12 - count($related))
                ->getQuery()->getResult();

            $related = $otherRelated;
        }
        foreach ($related as $productR) {
            if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
                $productR->setFavorite($this->get('product_service')->existProductInFavorite($productR->getId(), $this->getUser()->getId()));
                $offerPrice = $this->get('product_service')->findProductOfferPrice($productR);
                $productR->setPriceOffer($offerPrice);
            }
            $offerPrice = $this->get('product_service')->findProductOfferPrice($productR);
            $productR->setPriceOffer($offerPrice);
        }

        $images = $product->getImages()->toArray();
        foreach ($product->getComboProducts() as $comboProduct) {
          $offerPrice = $this->get('product_service')->findProductOfferPrice($comboProduct->getProduct());
          $comboProduct->getProduct()->setPriceOffer($offerPrice);

          foreach ($comboProduct->getProduct()->getImages() as $image) {
            $images[] = $image;
          }
        }

        foreach ($product->getComplementaryProducts() as $complementaryProduct) {
          $offerPrice = $this->get('product_service')->findProductOfferPrice($complementaryProduct->getProduct());
          $complementaryProduct->getProduct()->setPriceOffer($offerPrice);
        }

        return $this->render(':site:product-details.html.twig', [
            'product' => $product,
            'comboChunks' => array_chunk($product->getComboProducts()->toArray(), 3),
            'complementaryProducts' => $product->getComplementaryProducts()->toArray(),
            'imageSets' => array_chunk($images, 3),
            'home' => $home,
            'membership' => $membership,
            'currentDate' => new \DateTime(),
            'related' => $related,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="shop-cart", path="/carrito-compras")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function shopCartAction(Request $request)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);

        $memberNumber = json_decode($request->request->get('memberNumber', false), true);
        $transportCost = json_decode($request->request->get('transportCost', false), true);
        $paymentType = $request->request->get('paymentType', false);
        $paymentCurrency = $request->request->get('paymentCurrency', false);
        $requestProducts = $request->request->get('products', []);
        if (!is_array($requestProducts)) {
          $requestProducts = json_decode($requestProducts, true);
        }

        $productsDB = $this->get('shop_cart_service')->getShopCartProducts($this->getUser());
        $shopCartBags = $this->get('shop_cart_service')->getShopCartBags($this->getUser());

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        $dto = new CheckOutDTO();
        $dto->setProducts(json_encode($productsDB));

        $user = $this->getUser();
        if ($user) {
          $dto->setFirstName($user->getFirstName());
          $dto->setLastName($user->getLastName());
          $dto->setEmail($user->getEmail());
          $dto->setAddress($user->getAddress());
          $dto->setMovil($user->getMobileNumber());
          $dto->setPhone($user->getHomeNumber());
        }

        $form = $this->createForm(CheckOutType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $productsResponse = [];
            $totalPriceBase = 0;
            $cucExtra = 0;
            $bagsExtra = 0;
            $memberBalance = 0;
            foreach ($requestProducts as $product) {
                if (!array_key_exists('type', $product)) {
                    $productDB = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->find($product['id']);

                    $totalPriceBase += $product['price'] * $product['count'];

                    $productsResponse[] = [
                        'price' => $product['price'],
                        'product' => $productDB,
                        'count' => $product['count'],
                    ];
                } else {
                    $totalPriceBase += $product['price'] * $product['count'];

                    $productsResponse[] = [
                        'price' => $product['price'],
                        'product' => $product,
                        'type' => 'target',
                        'count' => $product['count'],
                    ];
                }
            }

            $totalPrice = $totalPriceBase;

            $discount = 0;
            $balanceDiscount = 0;
            $remainingBalance = 0;
            if ($memberNumber) {
              $discount = ceil($totalPriceBase * 0.1);
              $balanceDiscount = ceil($this->getUser()->getMember()->getBalance());
              $totalPrice -= $discount;
              $totalPrice -= $balanceDiscount;
            }

            $twoStepExtra = 0;
            if ($paymentType == 'two-steps') {
              $twoStepExtra = ceil($totalPriceBase * 0.1);
              $totalPrice += $twoStepExtra;
            }

            if ($paymentCurrency == 'cuc') {
              $cucExtra = ceil($totalPriceBase * 0.3);
              $totalPrice += $cucExtra;
            }

            if ($shopCartBags != null) {
              $bagsExtra = $shopCartBags->getNumberOfPayedBags() - $shopCartBags->getNumberOfFreeBags() * 7;
              if ($bagsExtra > 0) {
                $totalPrice += $bagsExtra;
              }
            }

            if ($totalPrice < 0) {
              $remainingBalance = 0 - $totalPrice;
              $totalPrice = 0;
            }

            if ($memberNumber) {
              $memberBalance = ceil($totalPrice * 0.05);
            }

            $totalPrice += $transportCost;
            $data = $form->getData();
            if ($data->getIgnoreTransport() == "true") {
              $totalPrice -= $transportCost;
              $transportCost = 0;
            }

            $repoClient = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\Client');
            $newClient = false;
            $client = $repoClient->findOneBy(['email' => $data->getEmail()]);
            if (null == $client) {
                $newClient = true;
                $client = new Client();
            }

            $client->setFirstName($data->getFirstName());
            $client->setLastName($data->getLastName());
            $client->setEmail($data->getEmail());
            $client->setAddress($data->getAddress());
            $client->setMovil($data->getMovil());
            $client->setPhone($data->getPhone());
            $this->getDoctrine()->getManager()->persist($client);

            $comboDiscount = 0;
            if ($data->getType() == "facture" && $this->getUser() && $this->getUser()->hasRole("ROLE_COMMERCIAL")) {
              $facture = new Facture();
              $facture->setClient($client);
              if ($data->getRequest() != "0") {
                $req = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\Request')->find((int) $data->getRequest());
                $facture->setRequest($req);
              }
              if ($data->getPrefacture() != "0") {
                $prefacture = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\PreFacture')->find((int) $data->getPrefacture());
                $facture->setPreFacture($prefacture);
              }

              foreach ($productsResponse as $productR) {
                if (array_key_exists('type', $productR)) {
                  $factureCard = new FactureCard();
                  $factureCard->setCount($productR['count']);
                  $factureCard->setFacture($facture);
                  $factureCard->setPrice($productR['price']);
                  $this->getDoctrine()->getManager()->persist($factureCard);
                  $facture->addFactureCard($factureCard);
                } else {
                  $product = $productR['product'];

                  if (count($product->getComboProducts()) > 0) {
                    foreach ($product->getComboProducts() as $comboProduct) {
                      $this->CreateProduct(
                        3,
                        new FactureProduct(),
                        $facture,
                        $comboProduct->getProduct(),
                        $comboProduct->getProduct()->getPrice(),
                        $comboProduct->getCount() * $productR['count']
                      );
                      $comboDiscount += $comboProduct->getProduct()->getPrice() * $comboProduct->getCount() * $productR['count'];
                    }
                    $comboDiscount -= $productR['price'];
                  } else {


                      if ($product->getInStore()){
                          $countProductsFacture = $productR['count'];
                          $countInStore = $product->getStoreCount();
                          $countProductsFacture = min($countProductsFacture, $countInStore);
                          $newCount = $countInStore - $countProductsFacture;
                          $product->setStoreCount($countProductsFacture);
                      }


                    $this->CreateProduct(
                      3,
                      new FactureProduct(),
                      $facture,
                      $product,
                      $productR['price'],
                      $productR['count']
                    );
                  }
                }
              }
            } elseif ($data->getType() == "prefacture" && $this->getUser() && $this->getUser()->hasRole("ROLE_COMMERCIAL")) {
              $prefacture = new PreFacture();
              $prefacture->setClient($client);
              if ($data->getRequest() != "0") {
                $req = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\Request')->find((int) $data->getRequest());
                $prefacture->setRequest($req);
              }

              foreach ($productsResponse as $productR) {
                if (array_key_exists('type', $productR)) {
                  $prefactureCard = new PreFactureCard();
                  $prefactureCard->setCount($productR['count']);
                  $prefactureCard->setPreFacture($prefacture);
                  $prefactureCard->setPrice($productR['price']);
                  $this->getDoctrine()->getManager()->persist($prefactureCard);
                  $prefacture->addPreFactureCard($prefactureCard);
                } else {
                  $product = $productR['product'];

                  if (count($product->getComboProducts()) > 0) {
                    foreach ($product->getComboProducts() as $comboProduct) {
                      $this->CreateProduct(
                        2,
                        new PreFactureProduct(),
                        $prefacture,
                        $comboProduct->getProduct(),
                        $comboProduct->getProduct()->getPrice(),
                        $comboProduct->getCount() * $productR['count']
                      );
                      $comboDiscount += $comboProduct->getProduct()->getPrice() * $comboProduct->getCount() * $productR['count'];
                    }
                    $comboDiscount -= $productR['price'];
                  } else {
                    $this->CreateProduct(
                      2,
                      new PreFactureProduct(),
                      $prefacture,
                      $product,
                      $productR['price'],
                      $productR['count']
                    );
                  }
                }
              }
            } elseif ($data->getType() == "external-request" && $this->getUser() && $this->getUser()->hasRole("ROLE_EXTERNAL")) {
              $externalRequest = new ExternalRequest();
              foreach ($productsResponse as $productR) {
                if (!array_key_exists('type', $productR)) {
                  $externalRequestProduct = new ExternalRequestProduct();
                  $externalRequestProduct->setExternalRequest($externalRequest);
                  $externalRequestProduct->setCount($productR['count']);
                  $externalRequestProduct->setProduct($productR['product']);
                  $this->getDoctrine()->getManager()->persist($externalRequestProduct);
                  $externalRequest->addExternalRequestProduct($externalRequestProduct);
                }
              }
            } else {
              $requestDB = new ProductRequest();
              $requestDB->setClient($client);

              foreach ($productsResponse as $productR) {
                if (array_key_exists('type', $productR)) {
                  $requestCard = new RequestCard();
                  $requestCard->setCount($productR['count']);
                  $requestCard->setRequest($requestDB);
                  $requestCard->setPrice($productR['price']);
                  $this->getDoctrine()->getManager()->persist($requestCard);
                  $requestDB->addRequestCard($requestCard);
                } else {
                  $product = $productR['product'];

                  if (count($product->getComboProducts()) > 0) {
                    foreach ($product->getComboProducts() as $comboProduct) {
                      $this->CreateProduct(
                        1,
                        new RequestProduct(),
                        $requestDB,
                        $comboProduct->getProduct(),
                        $comboProduct->getProduct()->getPrice(),
                        $comboProduct->getCount() * $productR['count']
                      );
                      $comboDiscount += $comboProduct->getProduct()->getPrice() * $comboProduct->getCount() * $productR['count'];
                    }
                    $comboDiscount -= $productR['price'];
                  } else {
                    $this->CreateProduct(
                      1,
                      new RequestProduct(),
                      $requestDB,
                      $product,
                      $productR['price'],
                      $productR['count']
                    );
                  }
                }
              }
            }

            if ($data->getType() == "facture" && $this->getUser() && $this->getUser()->hasRole("ROLE_COMMERCIAL")) {
              $facture->setDiscount($discount);
              $facture->setTwoStepExtra($twoStepExtra);
              $facture->setCucExtra($cucExtra);
              $facture->setBagsExtra($bagsExtra);
              $facture->setFirstClientDiscount(0);
              $facture->setComboDiscount($comboDiscount);
              $facture->setTransportCost($transportCost);
              $facture->setFinalPrice($totalPrice);
              $facture->setCommercial($this->getUser());
              $this->getDoctrine()->getManager()->persist($facture);
            } elseif ($data->getType() == "prefacture" && $this->getUser() && $this->getUser()->hasRole("ROLE_COMMERCIAL")) {
              $prefacture->setDiscount($discount);
              $prefacture->setTwoStepExtra($twoStepExtra);
              $prefacture->setCucExtra($cucExtra);
              $prefacture->setBagsExtra($bagsExtra);
              $prefacture->setFirstClientDiscount(0);
              $prefacture->setComboDiscount($comboDiscount);
              $prefacture->setTransportCost($transportCost);
              $prefacture->setFinalPrice($totalPrice);
              $prefacture->setCommercial($this->getUser());
              $this->getDoctrine()->getManager()->persist($prefacture);
            } elseif ($data->getType() == "external-request" && $this->getUser() && $this->getUser()->hasRole("ROLE_EXTERNAL")) {
              $finalPrice = 0;
              $weight = 0;
              foreach ($requestProducts as $product) {
                $productDB = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->find($product['id']);

                $finalPrice += $productDB->getIkeaPrice() * $product["count"];
                $weight += $productDB->getWeight() * $product["count"];
              }

              $externalRequest->setFinalPrice($finalPrice);
              $externalRequest->setWeight($weight);
              $externalRequest->setSellPrice($data->getSellPrice());
              $externalRequest->setBudget($data->getBudget());
              $externalRequest->setTicketPrice($data->getTicketPrice());
              $externalRequest->setProviderProfit($data->getProviderProfit());
              $externalRequest->setPayment($data->getPayment());
              $externalRequest->setCreationDate(new \DateTime('now'));
              $externalRequest->setDate(new \DateTime($data->getDate()));
              $externalRequest->setState("Sin estado");
              $this->getDoctrine()->getManager()->persist($externalRequest);
            } else {
              $requestDB->setDiscount($discount);
              $requestDB->setBalanceDiscount($balanceDiscount);
              $requestDB->setTwoStepExtra($twoStepExtra);
              $requestDB->setCucExtra($cucExtra);
              $requestDB->setBagsExtra($bagsExtra);
              $requestDB->setFirstClientDiscount(0);
              $requestDB->setComboDiscount($comboDiscount);
              $requestDB->setTransportCost($transportCost);
              $requestDB->setFinalPrice($totalPrice);
              $this->getDoctrine()->getManager()->persist($requestDB);

              if ($memberNumber) {
                $this->getUser()->getMember()->setBalance($remainingBalance + $memberBalance);
              }
            }

            $this->getDoctrine()->getManager()->flush();

            if ($data->getType() == "facture" && $this->getUser() && $this->getUser()->hasRole("ROLE_COMMERCIAL")) {
              return $this->redirectToRoute('site_home');
            } elseif ($data->getType() == "prefacture" && $this->getUser() && $this->getUser()->hasRole("ROLE_COMMERCIAL")) {
              return $this->redirectToRoute('site_home');
            } elseif ($data->getType() == "external-request" && $this->getUser() && $this->getUser()->hasRole("ROLE_EXTERNAL")) {
              $users = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select("u")
                ->from("AppBundle\Entity\User", "u")
                ->where("u.roles like :roles")
                ->setParameter("roles", "%ROLE_EXTERNAL%")
                ->getQuery()
                ->getResult();

              foreach ($users as $user) {
                if ($user->getEmail() != null) {
                  $body = $this->renderView(':site:new-external-request-email.html.twig', [
                    'username' => $user->getFirstName().' '.$user->getLastName(),
                    'externalRequest' => $externalRequest,
                  ]);
                  $this->get('email_service')->send($config->getEmail(), 'Nueva orden de compra', $user->getEmail(), 'Nueva orden de compra', $body);
                }
              }
              return $this->redirectToRoute('site_home');
            } else {
              return $this->redirectToRoute('success_request', ['id' => $requestDB->getId()]);
            }
        }

        return $this->render(':site:shop-cart.html.twig', [
          'requests' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\Request')->findAll(),
          'prefactures' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\PreFacture')->findAll(),
          'form' => $form->createView(),
          'home' => $home,
          'membership' => $membership,
          'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
          'shopCartProducts' => $productsDB,
          'shopCartBags' => $shopCartBags,
          'terms' => $config->getTermAndConditions(),
          'privacy' => $config->getPrivacyPolicy(),
          'currentDate' => new \DateTime(),
          'categories' => $this->get('category_service')->getAll(),
        ]);
    }

    /**
     * @Route(name="persist_count_shop_car", path="/carrito-compras/product/{product}/{count}")
     *
     * @param Request $request
     * @param $product
     * @param $count
     *
     * @return JsonResponse
     *
     * @internal param Request $request
     */
    public function persistProductCountShopCarAction(Request $request, $product = -1, $count = -1)
    {
      $shopCartProducts = $this->getDoctrine()->getManager()->getRepository('AppBundle:ShopCartProduct')->findBy([
        'user' => $this->getUser()->getId(),
      ]);
      foreach ($shopCartProducts as $shopCartProduct) {
        if ($shopCartProduct->getUuid() == $product) {
          $shopCartProduct->setCount($count);
          $this->getDoctrine()->getManager()->persist($shopCartProduct);
          $this->getDoctrine()->getManager()->flush();
        }
      }

      $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
        'name' => 'Home',
      ]);

      $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
        'name' => 'Membresia',
      ]);

      $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

      return new JsonResponse([
        'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
        'html' => $this->renderView(':site:products-summary.html.twig', [
          'home' => $home,
          'membership' => $membership,
          'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
          'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
          'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
          'categories' => $this->get('category_service')->getAll(),
          'terms' => $config->getTermAndConditions(),
          'privacy' => $config->getPrivacyPolicy(),
        ])
      ]);
    }

    /**
     * @Route(name="empty_shop_cart", path="/carrito-compras/vaciar")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function emptyShopCarAction(Request $request)
    {
      $this->get('shop_cart_service')->emptyShopCart($this->getUser());

      return $this->redirectToRoute('site_home');
    }

    /**
     * @Route(name="services", path="/servicios")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function servicesAction(Request $request)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);
        $services = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Obras',
        ]);
        $inward = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Interiorismo',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render(':site:services.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'services' => $services,
            'inward' => $inward,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="inward", path="/interiorismo")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function inwardAction(Request $request)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);
        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Interiorismo',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render(':site:inward.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'page' => $page,
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="membership", path="/membresia")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function membershipAction(Request $request)
    {
        $config = $this->getDoctrine()->getRepository('AppBundle:Configuration')->find(1);
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);

        $products = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')
            ->createQueryBuilder('p')
            ->join('p.offers', 'o')
            ->where('o.startDate <= :current AND o.endDate >= :current')
            ->andWhere('o.onlyForMembers = 1')
            ->setParameter('current', new \DateTime(), Type::DATE)
            ->getQuery()
            ->getResult()
        ;

        return $this->render(':site:membership.html.twig', [
            'home' => $home,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'page' => $page,
            'membership' => $page,
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'categories' => $this->get('category_service')->getAll(),
            'products' => $products,
            'currentDate' => new \DateTime(),
        ]);
    }

    /**
     * @Route(name="help", path="/ayuda")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function helpAction(Request $request)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);
        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Ayuda',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render(':site:help.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'page' => $page,
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="project_details", path="/detalles-proyecto/{projectTitle}")
     *
     * @param Request $request
     * @param mixed   $projectTitle
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function projectDetailsAction(Request $request, $projectTitle)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);
        $inwardPage = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Interiorismo',
        ]);
        $servicesPage = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Obras',
        ]);

        $project = null;
        $inwardProjects = $inwardPage->getData()['services']['projects']['projects'];
        $servicesProjects = $servicesPage->getData()['services']['projects']['projects'];

        foreach ($inwardProjects as $inwardProject) {
            if ($inwardProject['title'] == $projectTitle) {
                $project = $inwardProject;
            }
        }
        foreach ($servicesProjects as $servicesProject) {
            if ($servicesProject['title'] == $projectTitle) {
                $project = $servicesProject;
            }
        }

        $products = [];
        if ($project['products'] != null) {
          $prod = implode(', ', $project['products']);
          $products = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')
            ->createQueryBuilder('p')
            ->where('p.id IN ('.$prod.')')
            ->orderBy('p.inStore', 'DESC')
            ->addOrderBy('p.popular', 'DESC')
            ->addOrderBy('p.price', 'ASC')
            ->addOrderBy('p.name', 'DESC')
            ->getQuery()
            ->getResult()
          ;
          foreach($products as $product) {
            $offerPrice = $this->get('product_service')->findProductOfferPrice($product);
            $product->setPriceOffer($offerPrice);
          }
        }

        $project['images'] = array_merge([$project], $project['extraImages']);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render(':site:project-details.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'project' => $project,
            'imageSets' => array_chunk($project['images'], 3),
            'products' => $products,
            'currentDate' => new \DateTime(),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="service_details", path="/detalles-servicio/{serviceTitle}/{pageName}")
     *
     * @param Request $request
     * @param mixed   $serviceTitle
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function serviceDetailsAction(Request $request, $serviceTitle, $pageName)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);

        $page = null;
        if ($pageName == "services") {
          $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
              'name' => 'Obras',
          ]);
        } elseif ($pageName == "inward") {
          $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
              'name' => 'Interiorismo',
          ]);
        }

        $service = null;
        $services = $page->getData()['services']['designersTeam']['services'];
        foreach ($services as $s) {
            if ($s['title'] == $serviceTitle) {
                $service = $s;
            }
        }

        $products = [];
        if ($service['products'] != null) {
          $prod = implode(', ', $service['products']);
          $products = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')
              ->createQueryBuilder('p')
              ->where('p.id IN ('.$prod.')')
              ->orderBy('p.inStore', 'DESC')
              ->addOrderBy('p.popular', 'DESC')
              ->addOrderBy('p.price', 'ASC')
              ->addOrderBy('p.name', 'DESC')
              ->getQuery()
              ->getResult()
          ;
          foreach($products as $product) {
            $offerPrice = $this->get('product_service')->findProductOfferPrice($product);
            $product->setPriceOffer($offerPrice);
          }
        }

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render(':site:service-details.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'service' => $service,
            'imageSets' => array_chunk($service['images'], 3),
            'products' => $products,
            'currentDate' => new \DateTime(),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="request_service", path="/solicitar-servicio")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function requestServiceAction(Request $request)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        $dto = new EmailDTO();
        $masterRequest = $this->get('request_stack')->getMasterRequest();
        $dto->setPath($masterRequest->getRequestUri());
        $form = $this->createForm(EmailType::class, $dto);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $email = $form->getData();
            $body = $this->renderView(':site:email-body.html.twig', [
                'firstName' => $email->getName(),
                'lastName' => $email->getLastName(),
                'email' => $email->getEmail(),
                'phone' => $email->getPhone(),
                'description' => $email->getText(),
            ]);
            $this->get('email_service')->send($email->getEmail(), $email->getName(), $config->getEmail(), 'Solicitud de Servicio', $body);
        }

        return $this->render(':site:request-service.html.twig', [
            'home' => $home,
            'form' => $form->createView(),
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="request_membership", path="/solicitar-membresia")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function requestMembershipAction(Request $request)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
          'name' => 'Membresia',
        ]);

        $dto = new MembershipRequestDTO();
        $masterRequest = $this->get('request_stack')->getMasterRequest();
        $dto->setPath($masterRequest->getRequestUri());
        $form = $this->createForm(MembershipRequestType::class, $dto);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $membershipRequest = $form->getData();

            $body = $this->renderView(':site:membership-email-body.html.twig', [
                'firstName' => $membershipRequest->getFirstName(),
                'lastName' => $membershipRequest->getLastName(),
                'email' => $membershipRequest->getEmail(),
                'phone' => $membershipRequest->getPhone(),
                'address' => $membershipRequest->getAddress(),
            ]);
            $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);
            $this->get('email_service')->send($membershipRequest->getEmail(), $membershipRequest->getFirstName(), $config->getEmail(), 'Solicitud de Membresia', $body);

            $products = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')
              ->createQueryBuilder('p')
              ->join('p.offers', 'o')
              ->where('o.startDate <= :current AND o.endDate >= :current')
              ->andWhere('o.onlyForMembers = 1')
              ->setParameter('current', new \DateTime(), Type::DATE)
              ->getQuery()
              ->getResult()
            ;

            return $this->render(':site:membership.html.twig', [
              'showSuccessToast' => true,
              'home' => $home,
              'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
              'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
              'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
              'page' => $membership,
              'terms' => $config->getTermAndConditions(),
              'privacy' => $config->getPrivacyPolicy(),
              'categories' => $this->get('category_service')->getAll(),
              'products' => $products,
              'currentDate' => new \DateTime(),
          ]);
        }

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render(':site:request-membership.html.twig', [
            'home' => $home,
            'page' => $membership,
            'form' => $form->createView(),
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="add_shop_card", path="/shop-card/add/{id}", methods={"POST", "GET"})
     *
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function addShopCartAction(Request $request, $id)
    {
        $shopCartProducts = $this->getDoctrine()->getManager()->getRepository('AppBundle:ShopCartProduct')->findBy([
          'user' => $this->getUser()->getId(),
        ]);
        $product = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->find($id);

        if ($request->query->get('complementary')) {
          foreach ($product->getComplementaryProducts() as $complementaryProduct) {
            $this->addShopCartProduct($complementaryProduct->getProduct()->getId(), $shopCartProducts);
          }
          $this->addShopCartProduct($id, $shopCartProducts);
        } else {
          $this->addShopCartProduct($id, $shopCartProducts);
        }

        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
          'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
          'name' => 'Membresia',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return new JsonResponse([
          'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
          'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
          'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
          'html' => $this->renderView(':site:products-summary.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
          ])
        ]);
    }

    /**
     * @Route(name="add_bags", path="/bags/add/{numberOfPayedBags}/{numberOfFreeBags}", methods={"POST", "GET"})
     *
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function addBagsAction(Request $request, $numberOfPayedBags, $numberOfFreeBags)
    {
        $shopCartBags = $this->getDoctrine()->getManager()->getRepository('AppBundle:ShopCartBags')->findOneBy([
          'user' => $this->getUser()->getId(),
        ]);

        if ($shopCartBags == null) {
          $shopCartBags = new ShopCartBags();
          $shopCartBags->setUser($this->getUser());
          $shopCartBags->setNumberOfPayedBags($numberOfPayedBags);
          $shopCartBags->setNumberOfFreeBags($numberOfFreeBags);
          $this->getDoctrine()->getManager()->persist($shopCartBags);
        } else {
          $shopCartBags->setNumberOfPayedBags($numberOfPayedBags);
          $shopCartBags->setNumberOfFreeBags($numberOfFreeBags);
        }
        $this->getDoctrine()->getManager()->flush();

        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
          'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
          'name' => 'Membresia',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return new JsonResponse([
          'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
          'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
          'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
          'html' => $this->renderView(':site:products-summary.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
          ])
        ]);
    }

    /**
     * @Route(name="remove_bags", path="/bags/remove", methods={"POST", "GET"})
     *
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function removeBagsAction(Request $request)
    {
        $shopCartBags = $this->getDoctrine()->getManager()->getRepository('AppBundle:ShopCartBags')->findOneBy([
          'user' => $this->getUser()->getId(),
        ]);

        if ($shopCartBags != null) {
          $shopCartBags->setNumberOfPayedBags(0);
        }
        $this->getDoctrine()->getManager()->flush();

        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
          'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
          'name' => 'Membresia',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return new JsonResponse([
          'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
          'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
          'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
          'html' => $this->renderView(':site:products-summary.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
          ])
        ]);
    }

    /**
     * @Route(name="remove_from_cart_shop", path="/shop-cart/remove/{id}", methods={"POST"})
     *
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function removeFromCartShop(Request $request, $id)
    {
      $shopCartProducts = $this->getDoctrine()->getManager()->getRepository('AppBundle:ShopCartProduct')->findBy([
        'user' => $this->getUser()->getId(),
      ]);
      foreach ($shopCartProducts as $shopCartProduct) {
        if ($shopCartProduct->getProductId() == $id) {
          $this->getDoctrine()->getManager()->remove($shopCartProduct);
          $this->getDoctrine()->getManager()->flush();
        }
      }

      $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
        'name' => 'Home',
      ]);
      $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
        'name' => 'Membresia',
      ]);

      $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

      return new JsonResponse([
        'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
        'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
        'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
        'html' => $this->renderView(':site:products-summary.html.twig', [
          'home' => $home,
          'membership' => $membership,
          'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
          'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
          'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
          'categories' => $this->get('category_service')->getAll(),
          'terms' => $config->getTermAndConditions(),
          'privacy' => $config->getPrivacyPolicy(),
        ])
      ]);
    }


    /**
     * @Route(name="success_request", path="/pedido-correcto/{id}")
     *
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function successRequestAction(Request $request, $id)
    {
        $requestDB = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\Request')->find($id);
        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);
        $client = $requestDB->getClient();

        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
          'name' => 'Pedido Realizado',
        ]);
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);


        $productsResponse = [];
        foreach ($requestDB->getRequestProducts() as $product) {
          $productsResponse[] = [
              'price' => $product->getProductPrice(),
              'product' => $product->getProduct(),
              'count' => $product->getCount(),
          ];
        }

        foreach ($requestDB->getRequestCards() as $card) {
          $productsResponse[] = [
              'price' => $card->getPrice(),
              'count' => $card->getCount(),
          ];
        }

        $inStore = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->findBy([
            'inStore' => true,
        ], null, 3);

        $body = $this->renderView(':site:request-email.html.twig', [
            'request' => $requestDB,
            'home' => $home,
            'inStore' => $inStore,
            'products' => $productsResponse,
            'membership' => $membership,
            'forClient' => false,
        ]);

        $bodyClient = $this->renderView(':site:request-email.html.twig', [
            'request' => $requestDB,
            'inStore' => $inStore,
            'home' => $home,
            'products' => $productsResponse,
            'membership' => $membership,
            'forClient' => true,
        ]);

        $this->get('email_service')->send($client->getEmail(), $client->getFirstName().' '.$client->getLastName(), $config->getEmail(), 'Pedido realizardo a través de la WEB', $body);
        $this->get('email_service')->send($config->getEmail(), 'Equipo comercial Conceptos', $client->getEmail(), 'Pedido realizado a través de la WEB', $bodyClient);

        $this->get('shop_cart_service')->emptyShopCart($this->getUser());
        $this->get('shop_cart_service')->emptyShopCartBags($this->getUser());

        $request->getSession()->set('successRequestToast', true);
        return $this->redirectToRoute('site_home');
    }

    /**
     * @Route(name="print_request", path="/imprimir-pedido/{id}")
     *
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function printRequestAction(Request $request, $id)
    {
        $requestDB = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\Request')->find($id);

        $productsResponse = [];
        foreach ($requestDB->getRequestProducts() as $product) {
          $productDB = $product->getProduct();

          $productsResponse[] = [
              'name' => $productDB->getName(),
              'image' => $productDB->getMainImage(),
              'code' => $productDB->getCode(),
              'count' => $product->getCount(),
              'price' => $product->getProductPrice(),
              'product' => $productDB,
          ];
        }

        foreach ($requestDB->getRequestCards() as $card) {
          $price = $card->getPrice();

          $productsResponse[] = [
              'name' => 'Tarjeta de $'.$price,
              'code' => $price,
              'count' => $card->getCount(),
              'price' => $price,
          ];
        }

        return $this->render(':site:request-export-pdf.html.twig', [
            'request' => $requestDB,
            'products' => $productsResponse,
            'home' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy(['name' => 'Home']),
            'membership' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy(['name' => 'Membresia']),
        ]);
    }

    /**
     * @Route(name="print_prefacture", path="/imprimir-prefactura/{id}")
     *
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function printPreFactureAction(Request $request, $id)
    {
        $prefactureDB = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\PreFacture')->find($id);

        $numberOfProducts = 0;
        $subtotal = 0;
        $productsResponse = [];
        foreach ($prefactureDB->getPreFactureProducts() as $product) {
          $productDB = $product->getProduct();

          if ($productDB != null) {
            $airplane = 'Marítimo';
            if ($product->getIsAriplaneForniture() || $product->getIsAriplaneMattress()) {
              $airplane = 'Aéreo';
            }

            $numberOfProducts += $product->getCount();
            $subtotal += $product->getCount() * $product->getProductPrice();
            $productsResponse[] = [
                'name' => $productDB->getName(),
                'image' => $productDB->getMainImage(),
                'code' => $productDB->getCode(),
                'item' => $productDB->getItem(),
                'count' => $product->getCount(),
                'price' => $product->getProductPrice(),
                'product' => $productDB,
                'airplane' => $airplane,
            ];
          }
        }

        foreach ($prefactureDB->getPreFactureCards() as $card) {
          $price = $card->getPrice();

          $numberOfProducts += $card->getCount();
          $subtotal += $card->getCount() * $price;
          $productsResponse[] = [
              'name' => 'Tarjeta de $'.$price,
              'item' => 'Tarjeta de $'.$price,
              'code' => $price,
              'count' => $card->getCount(),
              'price' => $price,
              'airplane' => 'NINGUNO',
          ];
        }

        $html = $this->renderView(':site:prefacture-export-pdf.html.twig', [
            'prefacture' => $prefactureDB,
            'products' => $productsResponse,
            'numberOfProducts' => $numberOfProducts,
            'subtotal' => $subtotal,
            'firstPayment' => ceil($prefactureDB->getFinalPrice() / 1.8),
            'home' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy(['name' => 'Home']),
            'membership' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy(['name' => 'Membresia']),
        ]);

        $dompdf = new Dompdf(array('enable_remote' => true));
        $dompdf->loadHtml($html);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->render();
        return $dompdf->stream(
          "Prefactura-".$prefactureDB->getId()."-".date_format($prefactureDB->getDate(), "Y").".pdf",
          ["Attachment" => true]
        );
    }

    /**
     * @Route(name="print_facture", path="/imprimir-factura/{id}")
     *
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function printFactureAction(Request $request, $id)
    {
      $factureDB = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\Facture')->find($id);

      $numberOfProducts = 0;
      $subtotal = 0;
      $productsResponse = [];
      foreach ($factureDB->getFactureProducts() as $product) {
        $productDB = $product->getProduct();

        if ($productDB != null) {
          $airplane = 'Marítimo';
          if ($product->getIsAriplaneForniture() || $product->getIsAriplaneMattress()) {
            $airplane = 'Aéreo';
          }

          $numberOfProducts += $product->getCount();
          $subtotal += $product->getCount() * $product->getProductPrice();
          $productsResponse[] = [
              'name' => $productDB->getName(),
              'image' => $productDB->getMainImage(),
              'code' => $productDB->getCode(),
              'item' => $productDB->getItem(),
              'count' => $product->getCount(),
              'price' => $product->getProductPrice(),
              'product' => $productDB,
              'airplane' => $airplane,
          ];
        }
      }

      foreach ($factureDB->getFactureCards() as $card) {
        $price = $card->getPrice();

        $numberOfProducts += $card->getCount();
        $subtotal += $card->getCount() * $price;
        $productsResponse[] = [
            'name' => 'Tarjeta de $'.$price,
            'item' => 'Tarjeta de $'.$price,
            'code' => $price,
            'count' => $card->getCount(),
            'price' => $price,
            'airplane' => 'NINGUNO',
        ];
      }

      $html = $this->renderView(':site:facture-export-pdf.html.twig', [
          'facture' => $factureDB,
          'products' => $productsResponse,
          'numberOfProducts' => $numberOfProducts,
          'subtotal' => $subtotal,
          'firstPayment' => ceil($factureDB->getFinalPrice() / 1.8),
          'home' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy(['name' => 'Home']),
          'membership' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy(['name' => 'Membresia']),
      ]);

      $dompdf = new Dompdf(array('enable_remote' => true));
      $dompdf->loadHtml($html);
      $dompdf->set_option('isHtml5ParserEnabled', true);
      $dompdf->render();
      return $dompdf->stream(
        "Factura-".$factureDB->getId()."-".date_format($factureDB->getDate(), "Y").".pdf",
        ["Attachment" => true]
      );
    }

    /**
     * @Route(name="email_notification", path="/notification/email/send")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function handleEmailNotificationAction(Request $request)
    {
        $dto = new EmailDTO();
        $masterRequest = $this->get('request_stack')->getMasterRequest();
        $dto->setPath($masterRequest->getRequestUri());
        $form = $this->createForm(EmailType::class, $dto);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $email = $form->getData();
            $body = $this->renderView(':site:email-body.html.twig', [
                'name' => $email->getName(),
                'lastName' => $email->getLastName(),
                'email' => $email->getEmail(),
                'phone' => $email->getPhone(),
                'description' => $email->getText(),
            ]);
            $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);
            $this->get('email_service')->send($email->getEmail(), $email->getName(), $config->getEmail(), 'Contacto de sitio WEB', $body);
            $path = $form->get('path')->getData();

            return $this->redirect($path);
        }

        return $this->render(':site:email.html.twig', [
            'form' => $form->createView(),
            'home' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
                'name' => 'Home',
            ]),
        ]);
    }

    /**
     * @Route(name="send_email_notification", path="/notification/email/send", methods={"POST"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendEmailNotificationAction(Request $request)
    {
        $path = $request->request->get('path');

        return $this->redirect($path);
    }

    /**
     * @Route(name="add_to_favorite", path="/product/favorite/add/{id}", methods={"POST"})
     *
     * @param $id
     *
     * @throws \Exception
     *
     * @return JsonResponse
     */
    public function addProductToFavoriteAction($id)
    {
        $user = $this->getUser();
        if (!$this->get('product_service')->existProductInFavorite($id, $user->getId())) {
            $favoriteProduct = new FavoriteProduct();
            $product = $this->get('product_service')->find($id);
            $favoriteProduct->setProduct($product);
            $favoriteProduct->setUser($user);
            $favoriteProduct->setDate(new \DateTime());
            $this->getDoctrine()->getManager()->persist($favoriteProduct);
            $this->getDoctrine()->getManager()->flush();
        }

        return new JsonResponse();
    }

    /**
     * @Route(name="remove_from_favorite", path="/product/favorite/remove/{id}", methods={"POST"})
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function removeProductToFavoriteAction($id)
    {
        $user = $this->getUser();
        $favoriteProduct = $this->get('member_service')->findFavoriteProduct($id, $user->getId());
        if (null != $favoriteProduct) {
            $this->getDoctrine()->getManager()->remove($favoriteProduct);
            $this->getDoctrine()->getManager()->flush();
        }

        return new JsonResponse();
    }

    /**
     * @Route(name="favorite_products", path="/favoritos")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function favoriteProductsAction(Request $request)
    {
      $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
        'name' => 'Home',
      ]);

      $products = $this->get('member_service')->findFavoriteProducts($this->getUser()->getId());
      foreach ($products as $product) {
        $product->setFavorite(true);
        $offerPrice = $this->get('product_service')->findProductOfferPrice($product);
        $product->setPriceOffer($offerPrice);
      }

      $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

      return $this->render(':site:favorite-products.html.twig', [
        'home' => $home,
        'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
        'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
        'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
        'products' => $products,
        'categories' => $this->get('category_service')->getAll(),
        'terms' => $config->getTermAndConditions(),
        'privacy' => $config->getPrivacyPolicy(),
        'currentDate' => new \DateTime(),
      ]);
    }

    /**
     * @Route(name="external_requests", path="/ordenes de compra")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function externalRequestsAction(Request $request)
    {
      $externalRequests = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\ExternalRequest')->findBy([
        'user' => null,
      ]);
      foreach($externalRequests as $externalRequest) {
        $remainingTimeFromCreation = $externalRequest->getCreationDate()->diff(new \DateTime('now'));
        $externalRequest->setRemainingTimeFromCreation($remainingTimeFromCreation);

        foreach($externalRequest->getExternalRequestProducts() as $externalRequestProduct) {
          $offerPrice = $this->get('product_service')->findProductOfferPrice($externalRequestProduct->getProduct());
          $externalRequestProduct->getProduct()->setPriceOffer($offerPrice);
        }
      }

      $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
        'name' => 'Home',
      ]);

      $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

      return $this->render(':site:external-requests.html.twig', [
        'externalRequests' => $externalRequests,
        'home' => $home,
        'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
        'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
        'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
        'categories' => $this->get('category_service')->getAll(),
        'terms' => $config->getTermAndConditions(),
        'privacy' => $config->getPrivacyPolicy(),
        'currentDate' => new \DateTime(),
      ]);
    }

    /**
     * @Route(name="accept_external_request", path="/accept-external-request/{id}", methods={"POST", "GET"})
     *
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function acceptExternalRequestAction(Request $request, $id)
    {
      $user = $this->getUser();
      $externalRequest = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\ExternalRequest')->find($id);

      if ($externalRequest->getUser() != null) {
        return new JsonResponse(-1);
      } else {
        $externalRequest->setUser($user);
        $externalRequest->setAcceptDate(new \DateTime());
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse($id);
      }
    }

    /**
     * @Route(name="export_external_request", path="/export-external-request/{id}", methods={"POST", "GET"})
     *
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function exportExternalRequestAction(Request $request, $id)
    {
      $user = $this->getUser();
      $externalRequest = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\ExternalRequest')->find($id);

      $html = $this->renderView(':site:external-requests-pdf.html.twig', [
        'externalRequest' => $externalRequest,
        'externalRequestProducts' => $externalRequest->getExternalRequestProducts(),
        'home' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy(['name' => 'Home']),
      ]);

      $dompdf = new Dompdf(array('enable_remote' => true));
      $dompdf->loadHtml($html);
      $dompdf->set_option('isHtml5ParserEnabled', true);
      $dompdf->render();
      return $dompdf->stream(
        "Pedido-".$externalRequest->getId().".pdf",
        ["Attachment" => true]
      );
    }

    /**
     * @Route(name="update_external_request_state", path="/update-external-request-state/{id}", methods={"POST", "GET"})
     *
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function updateExternalRequestStateAction(Request $request, $id)
    {
      $state = $request->request->get('state');

      $user = $this->getUser();
      $externalRequest = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\ExternalRequest')->find($id);
      $externalRequest->setState($state);
      $this->getDoctrine()->getManager()->flush();

      return new JsonResponse($state);
    }

    /**
     * @Route(name="request_status", path="/estado-pedidos")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function requestStatusAction(Request $request)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);

        $userMail = $this->getUser()->getEmail();
        $clientRequests = [];
        $persistedRequests = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\Request')->findAll();
        foreach ($persistedRequests as $persistedRequest) {
          if ($persistedRequest->getClient()->getEmail() == $userMail) {
            $clientRequests[] = $persistedRequest;
          }
        }

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render(':site:request-status.html.twig', [
            'home' => $home,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'requests' => $clientRequests,
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'currentDate' => new \DateTime(),
        ]);
    }

    /**
     * @Route(name="evaluate_product", path="/product/evaluate/{productId}", methods={"POST", "GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function evaluateProductAction(Request $request, $productId)
    {
      $generalOpinion = $request->request->get('generalOpinion');
      $priceQualityEvaluation = $request->request->get('priceQualityEvaluation');
      $utilityEvaluation = $request->request->get('utilityEvaluation');
      $durabilityEvaluation = $request->request->get('durabilityEvaluation');
      $qualityEvaluation = $request->request->get('qualityEvaluation');
      $designEvaluation = $request->request->get('designEvaluation');
      $generalEvaluation = $request->request->get('generalEvaluation');
      $opinion = $request->request->get('opinion');
      $recommended = json_decode($request->request->get('recommended'));
      $product = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->find($productId);
      $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

      $evaluations = $this->getDoctrine()->getManager()->getRepository('AppBundle:Evaluation')->findAll();
      foreach ($evaluations as $persistedEvaluation) {
        if ($persistedEvaluation->getUser()->getId() == $this->getUser()->getId() && $persistedEvaluation->getProduct()->getId() == $productId) {
          $persistedEvaluation->setGeneralOpinion($generalOpinion);
          $persistedEvaluation->setPriceQualityValue($priceQualityEvaluation);
          $persistedEvaluation->setUtilityValue($utilityEvaluation);
          $persistedEvaluation->setDurabilityValue($durabilityEvaluation);
          $persistedEvaluation->setQualityValue($qualityEvaluation);
          $persistedEvaluation->setDesignValue($designEvaluation);
          $persistedEvaluation->setEvaluationValue($generalEvaluation);
          $persistedEvaluation->setComment($opinion);
          $persistedEvaluation->setIsRecommended($recommended);
          $persistedEvaluation->setIsAccepted(false);
          $this->getDoctrine()->getManager()->persist($persistedEvaluation);
          $this->getDoctrine()->getManager()->flush();

          if ($opinion != null) {
            $body = $this->renderView(':site:new-evaluation-email.html.twig', [
              'user' => $this->getUser(),
              'evaluation' => $persistedEvaluation,
            ]);
            $this->get('email_service')->send($config->getEmail(), 'Nueva evaluación', $config->getEmail(), 'Nueva evaluación', $body);
          }

          return new JsonResponse([
            'html' => $this->renderView(':site/components:change-product-evaluation.html.twig', [
              'product' => $product,
            ])
          ]);
        }
      }

      $evaluation = new Evaluation();
      $evaluation->setGeneralOpinion($generalOpinion);
      $evaluation->setPriceQualityValue($priceQualityEvaluation);
      $evaluation->setUtilityValue($utilityEvaluation);
      $evaluation->setDurabilityValue($durabilityEvaluation);
      $evaluation->setQualityValue($qualityEvaluation);
      $evaluation->setDesignValue($designEvaluation);
      $evaluation->setEvaluationValue($generalEvaluation);
      $evaluation->setComment($opinion);
      $evaluation->setIsRecommended($recommended);
      $evaluation->setIsAccepted(false);
      $evaluation->setUser($this->getUser());
      $evaluation->setProduct($product);
      $this->getDoctrine()->getManager()->persist($evaluation);
      $this->getDoctrine()->getManager()->flush();

      if ($opinion != null) {
        $body = $this->renderView(':site:new-evaluation-email.html.twig', [
          'user' => $this->getUser(),
          'evaluation' => $persistedEvaluation,
        ]);
        $this->get('email_service')->send($config->getEmail(), 'Nueva evaluación', $config->getEmail(), 'Nueva evaluación', $body);
      }

      return new JsonResponse([
        'html' => $this->renderView(':site/components:change-product-evaluation.html.twig', [
          'product' => $product,
        ])
      ]);
    }

    /**
     * @Route(name="accept_evaluation", path="/accept-evaluation/{evaluationId}", methods={"POST", "GET"})
     *
     * @return JsonResponse
     */
    public function acceptEvaluationAction(Request $request, $evaluationId)
    {
      $evaluation = $this->getDoctrine()->getManager()->getRepository('AppBundle:Evaluation')->find($evaluationId);

      $evaluation->setIsAccepted(true);
      $this->getDoctrine()->getManager()->persist($evaluation);
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('site_home');
    }

    /**
     * @Route(name="products_summary", path="/resumen-productos")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productsSummaryAction(Request $request)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
          'name' => 'Membresia',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render(':site:products-summary.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    private function addShopCartProduct($id, $shopCartProducts, $count = 1)
    {
      $exist = false;
      foreach ($shopCartProducts as $shopCartProduct) {
        if ($shopCartProduct->getProductId() == $id) {
          $exist = true;
          $shopCartProduct->setCount($shopCartProduct->getCount() + $count);
          $this->getDoctrine()->getManager()->persist($shopCartProduct);
          $this->getDoctrine()->getManager()->flush();
        }
      }
      if (!$exist) {
        $shopCartProduct = new ShopCartProduct();
        $shopCartProduct->setProductId($id);
        $shopCartProduct->setCount($count);
        $shopCartProduct->setUuid(uniqid());
        $shopCartProduct->setUser($this->getUser());
        $shopCartProducts[] = $shopCartProduct;
        $this->getDoctrine()->getManager()->persist($shopCartProduct);
        $this->getDoctrine()->getManager()->flush();
      }
    }

    private function CreateProduct($type, $requestProd, $requestDB, $product, $productPrice, $count)
    {
      if ($type == 1) {
        $requestProd->setRequest($requestDB);
      } elseif ($type == 2) {
        $requestProd->setPrefacture($requestDB);
      } else {
        $requestProd->setFacture($requestDB);
      }


      $requestProd->setCount($count);
      $requestProd->setProduct($product);
      $requestProd->setProductPrice($productPrice);
      if ($productPrice > $product->getPrice()){
        $requestProd->setIsAriplaneForniture(true);
        $requestProd->setIsAriplaneMattress(true);
      } else {
        $requestProd->setIsAriplaneForniture(false);
        $requestProd->setIsAriplaneMattress(false);
      }

      $this->getDoctrine()->getManager()->persist($requestProd);

      if ($type == 1) {
        $requestDB->addRequestProduct($requestProd);
      } elseif ($type == 2) {
        $requestDB->addPreFactureProduct($requestProd);
      } else {
        $requestDB->addFactureProduct($requestProd);
      }
    }
}
