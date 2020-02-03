<?php

namespace AppBundle\Controller;

use AppBundle\DTO\CheckOutDTO;
use AppBundle\DTO\EmailDTO;
use AppBundle\DTO\MembershipRequestDTO;
use AppBundle\Entity\Evaluation;
use AppBundle\Entity\FavoriteProduct;
use AppBundle\Entity\Request\Client;
use AppBundle\Entity\Request\Request as ProductRequest;
use AppBundle\Entity\Request\Facture;
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
use Doctrine\DBAL\Types\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $currentDate = new \DateTime();

        $displayLoginError = $request->query->get('displayLoginError', false);

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
                $offersProduct = $this->get('product_service')->findOffersByProductAndDate($product->getId(), $currentDate);
                if (count($offersProduct) > 0) {
                    $product->setPriceOffer($offersProduct[0]->getPrice());
                }
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

        $products = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->findAll();

        $inStoreHighlight = null;
        $lastedHighlight = null;
        foreach ($products as $product) {
          if ($product->getIsHighlight()) {
            $offersInStore = $this->get('product_service')->findOffersByProductAndDate($product->getId(), $currentDate);
            if (count($offersInStore) > 0) {
                $product->setPriceOffer($offersInStore[0]->getPrice());
            }
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
            $offersInStore = $this->get('product_service')->findOffersByProductAndDate($product->getId(), $currentDate);
            if (count($offersInStore) > 0) {
                $product->setPriceOffer($offersInStore[0]->getPrice());
            }
            if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
                $product->setFavorite($this->get('product_service')->existProductInFavorite($product->getId(), $this->getUser()->getId()));
            }
        }

        foreach ($lasted as $product) {
            $offersInLast = $this->get('product_service')->findOffersByProductAndDate($product->getId(), $currentDate);
            if (count($offersInLast) > 0) {
                $product->setPriceOffer($offersInLast[0]->getPrice());
            }
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

        return $this->render(':site:home.html.twig', [
            'showSuccessToast' => $showSuccessToast,
            'displayLoginError' => $displayLoginError,
            'offers' => $offers,
            'offersImage' => $offersImage,
            'lasted' => $lasted,
            'lastedHighlight' => $lastedHighlight,
            'page' => $page,
            'membership' => $membership,
            'home' => $page,
            'lasts' => $lasts,
            'inStore' => $inStore,
            'inStoreDesktop' => array_chunk($inStore, 4),
            'inStoreHighlight' => $inStoreHighlight,
            'popularChunks' => array_chunk($populars, 6),
            'popularChunksDesktop' => array_chunk($populars, 12),
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
            'config' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1),
            'brands' => array_chunk($brands, 4),
            'currentDate' => new \DateTime(),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
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
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
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
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
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

        $result = [];
        $result += $this->get('page_service')->getHome();
        $result += $this->get('color_service')->getAll();
        $result += $this->get('material_service')->getAll();
        $result += $this->get('product_service')->getPriceRange();
        $result += $this->get('product_service')->filterProducts($request, $this->getUser());
        $result += ['categories' => $this->get('category_service')->getAll()];
        $result += ['terms' => $config->getTermAndConditions()];
        $result += ['privacy' => $config->getPrivacyPolicy()];
        $result += ['currentDate' => new \DateTime()];
        $result += ['page' => $page];
        $result += ['membership' => $membership];
        $result += ['count' => $this->countShopCart($request)];
        $result += ['shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true))];

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
        $product = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->find($id);
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $product->setFavorite($this->get('product_service')->existProductInFavorite($product->getId(), $this->getUser()->getId()));
        }
        $related = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->createQueryBuilder('p')
            ->where('p.name = :name AND p.id <> :current')
            ->setParameter('name', $product->getName())
            ->setParameter('current', $product->getId())
            ->orderBy('p.name', 'ASC')
            ->setMaxResults(12)
            ->getQuery()->getResult();

        if (count($related) < 21) {
            $categories = [];
            foreach ($product->getCategories() as $category) {
                $categories[] = $category->getId();
            }
            $otherRelated = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->createQueryBuilder('p')
                ->join('p.categories', 'c')
                ->where('c.id IN (:category) AND p.id <> :current')
                ->setParameter('category', $categories)
                ->setParameter('current', $product->getId())
                ->orderBy('p.name', 'ASC')
                ->setMaxResults(12 - count($related))
                ->getQuery()->getResult();

            $related = array_merge($related, $otherRelated);
        }
        foreach ($related as $productR) {
            if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
                $productR->setFavorite($this->get('product_service')->existProductInFavorite($productR->getId(), $this->getUser()->getId()));
            }
        }
        $offer = $this->getDoctrine()->getManager()->getRepository('AppBundle:Offer')->createQueryBuilder('o')
            ->join('o.products', 'p')
            ->where('o.startDate < :date AND o.endDate > :date AND p.id = :product')
            ->setParameter('date', new \DateTime(), Type::DATE)
            ->setParameter('product', $id)
            ->getQuery()->getResult();
        if (count($offer) > 0) {
            $offer = $offer[0];
        } else {
            $offer = null;
        }

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render(':site:product-details.html.twig', [
            'product' => $product,
            'imageSets' => array_chunk($product->getImages()->toArray(), 3),
            'offer' => $offer,
            'home' => $home,
            'membership' => $membership,
            'currentDate' => new \DateTime(),
            'related' => $related,
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
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

        $session = $request->getSession();
        if ($session->has('products')) {
            $products = json_decode($session->get('products'), true);
        } else {
            $products = [];
        }
        $productsDB = $this->getShopCartProducts($products);

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
            if ($memberNumber) {
              $discount = ceil($totalPriceBase * 0.1);
              $totalPrice -= $discount;
            }

            $twoStepExtra = 0;
            if ($paymentType == 'two-steps') {
              $twoStepExtra = ceil($totalPriceBase * 0.1);
              $totalPrice += $twoStepExtra;
            }

            $cucExtra = 0;
            if ($paymentCurrency == 'cuc') {
              $cucExtra = ceil($totalPriceBase * 0.2);
              $totalPrice += $cucExtra;
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

            if ($data->getType() != "request" && $this->getUser() && $this->getUser()->hasRole("ROLE_COMMERCIAL")) {
              if ($data->getType() == "facture") {
                $facture = new Facture();
                $facture->setClient($client);
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
                      $factureProduct = new FactureProduct();
                      $factureProduct->setCount($productR['count']);
                      $factureProduct->setFacture($facture);
                      $factureProduct->setProduct($productR['product']);
                      $factureProduct->setProductPrice($productR['price']);
                      if ($productR['price'] > $productR['product']->getPrice()){
                        $factureProduct->setIsAriplaneForniture(true);
                        $factureProduct->setIsAriplaneMattress(true);
                      } else {
                        $factureProduct->setIsAriplaneForniture(false);
                        $factureProduct->setIsAriplaneMattress(false);
                      }

                      $this->getDoctrine()->getManager()->persist($factureProduct);
                      $facture->addFactureProduct($factureProduct);
                  }
                }
              } else {
                $prefacture = new PreFacture();
                $prefacture->setClient($client);

                foreach ($productsResponse as $productR) {
                  if (array_key_exists('type', $productR)) {
                      $prefactureCard = new PreFactureCard();
                      $prefactureCard->setCount($productR['count']);
                      $prefactureCard->setPreFacture($prefacture);
                      $prefactureCard->setPrice($productR['price']);
                      $this->getDoctrine()->getManager()->persist($prefactureCard);
                      $prefacture->addPreFactureCard($prefactureCard);
                  } else {
                      $preFactureProduct = new PreFactureProduct();
                      $preFactureProduct->setCount($productR['count']);
                      $preFactureProduct->setPreFacture($prefacture);
                      $preFactureProduct->setProduct($productR['product']);
                      $preFactureProduct->setProductPrice($productR['price']);
                      if ($productR['price'] > $productR['product']->getPrice()){
                        $preFactureProduct->setIsAriplaneForniture(true);
                        $preFactureProduct->setIsAriplaneMattress(true);
                      } else {
                        $preFactureProduct->setIsAriplaneForniture(false);
                        $preFactureProduct->setIsAriplaneMattress(false);
                      }

                      $this->getDoctrine()->getManager()->persist($preFactureProduct);
                      $prefacture->addPreFactureProduct($preFactureProduct);
                  }
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
                    $requestProd = new RequestProduct();
                    $requestProd->setCount($productR['count']);
                    $requestProd->setRequest($requestDB);
                    $requestProd->setProduct($productR['product']);
                    $requestProd->setProductPrice($productR['price']);
                    if ($productR['price'] > $productR['product']->getPrice()){
                      $requestProd->setIsAriplaneForniture(true);
                      $requestProd->setIsAriplaneMattress(true);
                    } else {
                      $requestProd->setIsAriplaneForniture(false);
                      $requestProd->setIsAriplaneMattress(false);
                    }

                    $this->getDoctrine()->getManager()->persist($requestProd);
                    $requestDB->addRequestProduct($requestProd);
                }
              }
            }

            if ($data->getType() != "request" && $this->getUser() && $this->getUser()->hasRole("ROLE_COMMERCIAL")) {
              if ($data->getType() == "facture") {
                $facture->setDiscount($discount);
                $facture->setTwoStepExtra($twoStepExtra);
                $facture->setCucExtra($cucExtra);
                $facture->setFirstClientDiscount(0);
                $facture->setTransportCost($transportCost);
                $facture->setFinalPrice($totalPrice);
                $this->getDoctrine()->getManager()->persist($facture);
              } else {
                $prefacture->setDiscount($discount);
                $prefacture->setTwoStepExtra($twoStepExtra);
                $prefacture->setCucExtra($cucExtra);
                $prefacture->setFirstClientDiscount(0);
                $prefacture->setTransportCost($transportCost);
                $prefacture->setFinalPrice($totalPrice);
                $this->getDoctrine()->getManager()->persist($prefacture);
              }
            } else {
              $requestDB->setDiscount($discount);
              $requestDB->setTwoStepExtra($twoStepExtra);
              $requestDB->setCucExtra($cucExtra);
              $requestDB->setFirstClientDiscount(0);
              $requestDB->setTransportCost($transportCost);
              $requestDB->setFinalPrice($totalPrice);
              $this->getDoctrine()->getManager()->persist($requestDB);
            }

            $this->getDoctrine()->getManager()->flush();
            $request->getSession()->invalidate();

            if ($data->getType() != "request" && $this->getUser() && $this->getUser()->hasRole("ROLE_COMMERCIAL")) {
              return $this->redirectToRoute('site_home');
            } else {
              return $this->redirectToRoute('success_request', ['id' => $requestDB->getId()]);
            }
        }

        return $this->render(':site:shop-cart.html.twig', [
          'prefactures' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\PreFacture')->findAll(),
          'form' => $form->createView(),
          'home' => $home,
          'membership' => $membership,
          'count' => $this->countShopCart($request),
          'shopCartProducts' => $productsDB,
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
        $session = $request->getSession();
        if ($session->has('products')) {
            $products = json_decode($session->get('products'), true);
        } else {
            $products = [];
        }
        $productsDB = [];
        foreach ($products as $productS) {
            if ($product == $productS['uuid']) {
                $productS['count'] = $count;
            }
            $productsDB[] = $productS;
        }
        $session->set('products', json_encode($productsDB));

        $count = 0;
        foreach ($productsDB as $product) {
          $count += $product['count'];
        }

        return new JsonResponse([
          'count' => $count,
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
        $session = $request->getSession();
        $session->set('products', json_encode([]));

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
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
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
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
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
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
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
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
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
        }

        $project['images'] = array_merge([$project], $project['extraImages']);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render(':site:project-details.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
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
        }

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render(':site:service-details.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
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
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
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
              'count' => $this->countShopCart($request),
              'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
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
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="add_shop_card", path="/shop-card/add/{id}/{offer}", methods={"POST", "GET"}, defaults={"offer"="false"})
     *
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function addShopCartAction(Request $request, $id)
    {
        $session = $request->getSession();

        $products = [];
        if ($session->has('products')) {
            $products = json_decode($session->get('products'), true);
        }

        $exist = false;
        $index = 0;
        foreach ($products as $product) {
            if (
              (array_key_exists('id', $product) && $product['id'] == $id) ||
              (!array_key_exists('id', $product) && $product['product'] == $id)) {
                $exist = true;
                $products[$index]['count'] += 1;
            }
          $index += 1;
        }
        if (!$exist) {
            if ('target15' == $id || 'target25' == $id || 'target50' == $id || 'target100' == $id) {
                $products[] = [
                    'id' => $id,
                    'name' => $request->request->get('name'),
                    'uuid' => uniqid(),
                    'count' => 1,
                ];
            } else {
                $products[] = [
                    'product' => $id,
                    'uuid' => uniqid(),
                    'count' => 1,
                ];
            }
        }
        $session->set('products', json_encode($products));

        $count = 0;
        foreach ($products as $product) {
          $count += $product['count'];
        }

        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
          'name' => 'Membresia',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return new JsonResponse([
            'count' => $count,
            'exist' => $exist,
            'products' => json_encode($this->getShopCartProducts(json_decode($request->getSession()->get('products'), true))),
            'html' => $this->renderView(':site:products-summary.html.twig', [
              'home' => $home,
              'membership' => $membership,
              'count' => $this->countShopCart($request),
              'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
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
        $session = $request->getSession();
        if ($session->has('products')) {
            $products = json_decode($session->get('products'), true);
            $newProducts = [];
            foreach ($products as $product) {
                if (array_key_exists('id', $product)) {
                    if ($product['id'] != $id) {
                        $newProducts[] = $product;
                    }
                } elseif ($product['product'] != $id) {
                    $newProducts[] = $product;
                }
            }
        } else {
            $newProducts = [];
        }
        $session->set('products', json_encode($newProducts));

        $count = 0;
        foreach ($newProducts as $product) {
          $count += $product['count'];
        }

        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
          'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
          'name' => 'Membresia',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return new JsonResponse([
            'count' => $count,
            'products' => json_encode($this->getShopCartProducts(json_decode($request->getSession()->get('products'), true))),
            'html' => $this->renderView(':site:products-summary.html.twig', [
              'home' => $home,
              'membership' => $membership,
              'count' => $this->countShopCart($request),
              'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
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

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

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

        $productsResponse = [];
        foreach ($prefactureDB->getPreFactureProducts() as $product) {
          $productDB = $product->getProduct();

          $productsResponse[] = [
              'image' => $productDB->getMainImage(),
              'code' => $productDB->getCode(),
              'count' => $product->getCount(),
              'price' => $product->getProductPrice(),
              'product' => $productDB,
          ];
        }

        foreach ($prefactureDB->getPreFactureCards() as $card) {
          $price = $card->getPrice();

          $productsResponse[] = [
              'name' => 'Tarjeta de $'.$price,
              'code' => $price,
              'count' => $card->getCount(),
              'price' => $price,
          ];
        }

        return $this->render(':site:prefacture-export-pdf.html.twig', [
            'prefacture' => $prefactureDB,
            'products' => $productsResponse,
            'home' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy(['name' => 'Home']),
            'membership' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy(['name' => 'Membresia']),
        ]);
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

        $productsResponse = [];
        foreach ($factureDB->getFactureProducts() as $product) {
          $productDB = $product->getProduct();

          $productsResponse[] = [
              'image' => $productDB->getMainImage(),
              'code' => $productDB->getCode(),
              'count' => $product->getCount(),
              'price' => $product->getProductPrice(),
              'product' => $productDB,
          ];
        }

        foreach ($factureDB->getFactureCards() as $card) {
          $price = $card->getPrice();

          $productsResponse[] = [
              'name' => 'Tarjeta de $'.$price,
              'code' => $price,
              'count' => $card->getCount(),
              'price' => $price,
          ];
        }

        return $this->render(':site:facture-export-pdf.html.twig', [
            'facture' => $factureDB,
            'products' => $productsResponse,
            'home' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy(['name' => 'Home']),
            'membership' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy(['name' => 'Membresia']),
        ]);
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
        }

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render(':site:favorite-products.html.twig', [
            'home' => $home,
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
            'products' => $products,
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'currentDate' => new \DateTime(),
        ]);
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
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
            'requests' => $clientRequests,
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'currentDate' => new \DateTime(),
        ]);
    }

    /**
     * @Route(name="evaluate_product", path="/product/evaluate/{productId}/{evaluationValue}", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function evaluateProductAction($productId, $evaluationValue)
    {
        $evaluations = $this->getDoctrine()->getManager()->getRepository('AppBundle:Evaluation')->findAll();
        foreach ($evaluations as $persitedEvaluation) {
          if ($persitedEvaluation->getUser()->getId() == $this->getUser()->getId() && $persitedEvaluation->getProduct()->getId() == $productId) {
            $persitedEvaluation->setEvaluationValue($evaluationValue);
            $this->getDoctrine()->getManager()->persist($persitedEvaluation);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse();
          }
        }

        $evaluation = new Evaluation();
        $evaluation->setEvaluationValue($evaluationValue);
        $evaluation->setUser($this->getUser());
        $evaluation->setProduct($this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->find($productId));
        $this->getDoctrine()->getManager()->persist($evaluation);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse();
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
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    private function getShopCartProducts($products)
    {
      if ($products == null) {
        $products = [];
      }

      $productsDB = [];
      foreach ($products as $product) {
          if (array_key_exists('id', $product) && ('target15' == $product['id'] || 'target25' == $product['id'] || 'target50' == $product['id'] || 'target100' == $product['id'])) {
              $name = 'Tarjeta de 15 CUC';
              switch ($product['id']) {
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
                  'id' => $product['id'],
                  'uuid' => $product['uuid'],
                  'type' => 'target',
                  'price' => $price,
                  'count' => $product['count'],
                  'name' => $name,
              ];
          } else {
              $productDB = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->find($product['product']);
              $price = $productDB->getPrice();

              $offerExists = false;
              $offerDB = null;
              if ($productDB->getOffers() && $productDB->getOffers()[0]) {
                $offerDB = $this->getDoctrine()->getManager()->getRepository('AppBundle:Offer')->find($productDB->getOffers()[0]);
              }

              $categories = [];
              if ($offerDB) {
                $price = $offerDB->getPrice();
                $offerExists = true;
              } else {
                foreach ($productDB->getCategories() as $category) {
                  $categories[] = $category->getId();

                  if (($category->getOffers()[0]) && ((!$category->getOffers()[0]->getOnlyInStoreProducts()) or ($category->getOffers()[0]->getOnlyInStoreProducts() && $productDB->getInStore()))) {
                    $price = ceil($productDB->getPrice()*(1 - $category->getOffers()[0]->getPrice()/100));
                    $offerExists = true;
                  } else {
                    foreach ($category->getParents() as $parentCategory) {
                      if (($parentCategory->getOffers()[0]) && ((!$parentCategory->getOffers()[0]->getOnlyInStoreProducts()) or ($parentCategory->getOffers()[0]->getOnlyInStoreProducts() && $productDB->getInStore()))) {
                        $price = ceil($productDB->getPrice()*(1 - $parentCategory->getOffers()[0]->getPrice()/100));
                        $offerExists = true;
                      }
                    }
                  }
                }
              }

              $productsDB[] = [
                  'id' => $productDB->getId(),
                  'uuid' => $product['uuid'],
                  'price' => $price,
                  'offerExists' => $offerExists,
                  'count' => $product['count'],
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
                  'categories' => json_encode($categories),
              ];
          }
      }
      return $productsDB;
    }

    private function countShopCart(Request $request)
    {
      $total = 0;
      $session = $request->getSession();
      if ($session->has('products')) {
        $products = json_decode($session->get('products'), true);
        foreach ($products as $product) {
          $total += $product['count'];
        }
      }

      return $total;
    }
}
