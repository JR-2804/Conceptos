<?php

namespace AppBundle\Controller;

use AppBundle\Repository\OfferRepository;
use AppBundle\Repository\CategoryRepository;
use AppBundle\Repository\ConfigurationRepository;
use AppBundle\Repository\PageRepository;
use AppBundle\Repository\PostRepository;
use AppBundle\Repository\ProductRepository;
use Doctrine\DBAL\Types\Type;
use FontLib\Table\Type\name;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class FrontendController extends Controller
{

    /**
     * @Route(name="home", path="/new/")
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction(Request $request){

        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);


        $breadcrumbs = ['Inicio'];
        return $this->render(':new_site:home.html.twig',[
            'home'=>$page,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * @Route(name="store", path="/new/tienda")
     *
     * @param Request $request
     * @param OfferRepository $offerRepository
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function storeAction(Request $request)
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

        $breadcrumbs = ['Inicio', 'Tienda'];


        return $this->render('new_site/store.html.twig', [
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
//            'popularChunks' => array_chunk($populars, $rowsOfPopularProductsInMobile * $columnsOfPopularProductsInMobile),
            'popularChunks' => array_chunk($populars, 4),
            'inStoreChunksDesktop' => array_chunk($inStore, $rowsOfStoreProductsInDesktop * $columnsOfStoreProductsInDesktop),
            'inStoreChunks' => array_chunk($inStore, 4),
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
            'breadcrumbs' => $breadcrumbs
        ]);
    }


    /**
     * @Route(name="construction", path="/new/construccion")
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function constructionAction(Request $request){
        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        $services = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Obras',
        ]);

        $heroImage = $services->getData()['services']['header']['image']['path'];
        $constructionServices = $services->getData()['services']['designersTeam']['services'];
        $constructionWorks =$services->getData()['services']['projects']['projects'];

        $breadcrumbs = ['Inicio', 'Construcción'];
        return $this->render('new_site/construction.html.twig',[
            'home'=>$page,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'breadcrumbs' => $breadcrumbs,
            'services'=>$services,
            'constructionServices'=>$constructionServices,
            'constructionWorks'=>$constructionWorks,
            'heroImage'=>$heroImage,
        ]);
    }

    /**
     * @Route(name="construction_work", path="/new/construccion/obra/{work}")
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function constructionWorkAction(Request $request, $work){

        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);


        $breadcrumbs = ['Inicio', 'Construcción', 'Obras', $work];
        return $this->render('new_site/construction_work.html.twig',[
            'home'=>$page,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * @Route(name="construction_service", path="/new/construccion/servicio/{servicio}")
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function constructionServiceAction(Request $request, $servicio){

        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        $breadcrumbs = ['Inicio', 'Construcción', 'Servicios', $servicio];

        return $this->render('new_site/construction_service.html.twig',[
            'home'=>$page,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * @Route(name="design", path="/new/diseno")
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function desingAction(Request $request){

        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        $services = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Interiorismo',
        ]);

        $designServices = $services->getData()['services']['designersTeam']['services'];
        $designWorks =$services->getData()['services']['projects']['projects'];


        $breadcrumbs = ['Inicio', 'Diseño'];
        return $this->render('new_site/design.html.twig',[
            'home'=>$page,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'breadcrumbs' => $breadcrumbs,
            'designServices' => $designServices,
            'designWorks' => $designWorks,
        ]);
    }

    /**
     * @Route(name="desing_work", path="/new/diseno/obra/{work}")
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function desingWorkAction(Request $request, $work){

        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        $breadcrumbs = ['Inicio', 'Diseño', 'Obras', $work];
        return $this->render('new_site/desing_work.html.twig',[
            'home'=>$page,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'breadcrumbs' => $breadcrumbs
        ]);
    }


    /**
     * @Route(name="desing_service", path="/new/diseno/servicio/{service}")
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function desingServiceAction(Request $request, $service){

        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);


        $breadcrumbs = ['Inicio', 'Diseño', 'Servicios', $service];
        return $this->render('new_site/desing_services.html.twig',[
            'home'=>$page,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'breadcrumbs' => $breadcrumbs
        ]);
    }


    /**
     * @Route(name="store_section", path="/new/tienda/categorias")
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function storeSectionAction(Request $request){

        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        $breadcrumbs = ['Inicio', 'Tienda'];
        $data = [];
        $data += $this->get('product_service')->filterProducts($request, $this->getUser());
        $data += [
            'home'=>$page,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'breadcrumbs' => $breadcrumbs
        ];


        return $this->render('new_site/store_section.html.twig', $data);
    }

    /**
     * @Route(name="product", path="/new/tienda/producto/{id}")
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productDetailsAction(Request $request, $id){

        $page = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        $product = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->find($id);

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

        $breadcrumbs = ['Inicio', 'Tienda', 'Productos'];
        return $this->render('new_site/product_details.html.twig',[
            'home'=>$page,
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'breadcrumbs' => $breadcrumbs,
            'product' => $product,
            'imageSets' => array_chunk($images, 3),
            'relatedChunk' => array_chunk($related, 4)
        ]);
    }
}