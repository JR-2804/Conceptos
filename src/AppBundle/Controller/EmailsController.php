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
use Doctrine\ORM\EntityManager;
use http\Message\Body;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class EmailsController extends Controller
{

    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(name="preview_promotion_email", path="/previewPromotionEmail/{id}")
     *
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function previewPromotionEmail(Request $request, $id)
    {

        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);

        $promEmail = $this->getDoctrine()->getManager()->getRepository('AppBundle:PromotionEmail')->find($id);

        $emails = explode(';', $promEmail->getEmails());
        $tagUser = $promEmail->getTagUser();
        if ($tagUser == 'clientes') {
            $clients = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\Client')->findAll();
            foreach ($clients as $client) {
                $emails[] = $client->getEmail();
            }
        } elseif ($tagUser == 'miembros') {
            $members = $this->getDoctrine()->getManager()->getRepository('AppBundle:Member')->findAll();
            foreach ($members as $member) {
                $emails[] = $member->getEmail();
            }
        }

        echo '<ul style="height: 25vh; overflow-y: auto;">';
        foreach ($emails as $email)
            echo '<li>' . $email . '</li>';
        echo '</ul>';

        $subject = $promEmail->getSubject();

        $primaryPicture = $promEmail->getPrimaryPicture();
        $primaryTitle = $promEmail->getPrimaryTitle();

        $introTitle1 = $promEmail->getIntroTitle1();
        $introPicture1 = $promEmail->getIntroPicture1();
        $introContent1 = $promEmail->getIntroContent1();
        $introLink1 = $promEmail->getIntroLink1();

        $introTitle2 = $promEmail->getIntroTitle2();
        $introPicture2 = $promEmail->getIntroPicture2();
        $introContent2 = $promEmail->getIntroContent2();
        $introLink2 = $promEmail->getIntroLink2();

        $introTitle3 = $promEmail->getIntroTitle3();
        $introPicture3 = $promEmail->getIntroPicture3();
        $introContent3 = $promEmail->getIntroContent3();
        $introLink3 = $promEmail->getIntroLink3();

        $intros = [];
        if ($introTitle1 != null)
            array_push($intros, ['title' => $introTitle1, 'picture' => $introPicture1, 'content' => $introContent1, 'link' => $introLink1]);
        if ($introTitle2 != null)
            array_push($intros, ['title' => $introTitle2, 'picture' => $introPicture2, 'content' => $introContent2, 'link' => $introLink2]);
        if ($introTitle3 != null)
            array_push($intros, ['title' => $introTitle3, 'picture' => $introPicture3, 'content' => $introContent3, 'link' => $introLink3]);


        $offersTitle = $promEmail->getOffersTitle();
        $offers = $promEmail->getOffers();
        $linkOffers = $promEmail->getLinkOffers();
        $offersProducts_ = [];
        $offersProducts = [];
        if (count($offers) > 0) {

            foreach ($offers as $offer)
                foreach ($offer->getProducts() as $product)
                    $offersProducts_[] = ['product' => $product, 'offerPrice' => $offer->getPrice()];

            $offersProductsIndex = array_rand($offersProducts_, min([count($offersProducts_), 4]));
            if (is_array($offersProductsIndex))
                foreach ($offersProductsIndex as $index)
                    $offersProducts[] = $offersProducts_[$index];
            else
                $offersProducts[] = $offersProducts_[$offersProductsIndex];
        }

        $productsTitle = $promEmail->getProductsTitle();
        $products = $promEmail->getProducts();
        $linkProducts = $promEmail->getLinkProducts();
        $productsOffers = [];

        foreach ($products as $product) {
            $productsOffers[] = count($product->getOffers()) > 0;
        }

        $promotionTitle = $promEmail->getPromotionTitle();
        $promotionPicture = $promEmail->getPromotionPicture();
        $promotionContent = $promEmail->getPromotionContent();
        $promotionLink = $promEmail->getPromotionLink();

        $promotion = ['title' => $promotionTitle,
            'picture' => $promotionPicture,
            'content' => $promotionContent,
            'link' => $promotionLink];

        $blogTitle = $promEmail->getBlogTitle();
        $blogs = $promEmail->getBlogs();

        $servicesTitle = $promEmail->getServicesTitle();
        $linkServices = $promEmail->getLinkServices();

        $serviceTitle1 = $promEmail->getServiceTitle1();
        $servicePicture1 = $promEmail->getServicePicture1();
        $serviceContent1 = $promEmail->getServiceContent1();
        $serviceLink1 = $promEmail->getServiceLink1();

        $serviceTitle2 = $promEmail->getServiceTitle2();
        $servicePicture2 = $promEmail->getServicePicture2();
        $serviceContent2 = $promEmail->getServiceContent2();
        $serviceLink2 = $promEmail->getServiceLink2();
        $services = [];
        if ($serviceTitle1 != null) {
            array_push($services, ['title' => $serviceTitle1, 'picture' => $servicePicture1, 'content' => $serviceContent1, 'link' => $serviceLink1]);
        }
        if ($serviceTitle2 != null) {
            array_push($services, ['title' => $serviceTitle2, 'picture' => $servicePicture2, 'content' => $serviceContent2, 'link' => $serviceLink2]);
        }

        $footerPicture = $promEmail->getFooterPicture();
        $footerLink = $promEmail->getFooterPictureLink();

        return $this->render('site/promotionEmail/promotionEmail.html.twig', [
            'subject' => $subject,
            'home' => $home,
            'primaryPicture' => $primaryPicture,
            'primaryTitle' => $primaryTitle,
            'intros' => $intros,
            'offersTitle' => $offersTitle,
            'offers' => $offersProducts,
            'linkOffers' => $linkOffers,
            'productsTitle' => $productsTitle,
            'products' => $products,
            'linkProducts' => $linkProducts,
            'productsOffers' => $productsOffers,
            'promotion' => $promotion,
            'blogsTitle' => $blogTitle,
            'blogs' => $blogs,
            'servicesTitle' => $servicesTitle,
            'linkServices' => $linkServices,
            'services' => $services,
            'footerPicture' => $footerPicture,
            'footerLink' => $footerLink,
        ]);

    }

    /**
     * @Route(name="send_promotion_email", path="/sendPromotionEmail/{id}")
     *
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendPromotionEmail(Request $request, $id)
    {

        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);

        $promEmail = $this->getDoctrine()->getManager()->getRepository('AppBundle:PromotionEmail')->find($id);

        $emails = explode(';', $promEmail->getEmails());

        $tagUser = $promEmail->getTagUser();
        if ($tagUser == 'clientes') {
            $clients = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\Client')->findAll();
            foreach ($clients as $client) {
                $emails[] = $client->getEmail();
            }
        } elseif ($tagUser == 'miembros') {
            $members = $this->getDoctrine()->getManager()->getRepository('AppBundle:Member')->findAll();
            foreach ($members as $member) {
                $emails[] = $member->getEmail();
            }
        }

        echo '<ul style="height: 25vh; overflow-y: auto;">';
        foreach ($emails as $email)
            echo '<li>' . $email . '</li>';
        echo '</ul>';

        $subject = $promEmail->getSubject();
        $primaryPicture = $promEmail->getPrimaryPicture();
        $primaryTitle = $promEmail->getPrimaryTitle();

        $introTitle1 = $promEmail->getIntroTitle1();
        $introPicture1 = $promEmail->getIntroPicture1();
        $introContent1 = $promEmail->getIntroContent1();
        $introLink1 = $promEmail->getIntroLink1();

        $introTitle2 = $promEmail->getIntroTitle2();
        $introPicture2 = $promEmail->getIntroPicture2();
        $introContent2 = $promEmail->getIntroContent2();
        $introLink2 = $promEmail->getIntroLink2();

        $introTitle3 = $promEmail->getIntroTitle3();
        $introPicture3 = $promEmail->getIntroPicture3();
        $introContent3 = $promEmail->getIntroContent3();
        $introLink3 = $promEmail->getIntroLink3();

        $intros = [];
        if ($introTitle1 != null)
            array_push($intros, ['title' => $introTitle1, 'picture' => $introPicture1, 'content' => $introContent1, 'link' => $introLink1]);
        if ($introTitle2 != null)
            array_push($intros, ['title' => $introTitle2, 'picture' => $introPicture2, 'content' => $introContent2, 'link' => $introLink2]);
        if ($introTitle3 != null)
            array_push($intros, ['title' => $introTitle3, 'picture' => $introPicture3, 'content' => $introContent3, 'link' => $introLink3]);


        $offersTitle = $promEmail->getOffersTitle();
        $offers = $promEmail->getOffers();
        $linkOffers = $promEmail->getLinkOffers();
        $offersProducts_ = [];
        $offersProducts = [];
        if (count($offers) > 0) {
            foreach ($offers as $offer)
                foreach ($offer->getProducts() as $product)
                    $offersProducts_[] = ['product' => $product, 'offerPrice' => $offer->getPrice()];

            $offersProductsIndex = array_rand($offersProducts_, min([count($offersProducts_), 4]));
            if (is_array($offersProductsIndex))
                foreach ($offersProductsIndex as $index)
                    $offersProducts[] = $offersProducts_[$index];
            else
                $offersProducts[] = $offersProducts_[$offersProductsIndex];
        }

        $productsTitle = $promEmail->getProductsTitle();
        $products = $promEmail->getProducts();
        $linkProducts = $promEmail->getLinkProducts();
        $productsOffers = [];

        foreach ($products as $product) {
            $productsOffers[] = count($product->getOffers()) > 0;
        }

        $promotionTitle = $promEmail->getPromotionTitle();
        $promotionPicture = $promEmail->getPromotionPicture();
        $promotionContent = $promEmail->getPromotionContent();
        $promotionLink = $promEmail->getPromotionLink();

        $promotion = ['title' => $promotionTitle,
            'picture' => $promotionPicture,
            'content' => $promotionContent,
            'link' => $promotionLink];

        $blogTitle = $promEmail->getBlogTitle();
        $blogs = $promEmail->getBlogs();

        $servicesTitle = $promEmail->getServicesTitle();
        $linkServices = $promEmail->getLinkServices();

        $serviceTitle1 = $promEmail->getServiceTitle1();
        $servicePicture1 = $promEmail->getServicePicture1();
        $serviceContent1 = $promEmail->getServiceContent1();
        $serviceLink1 = $promEmail->getServiceLink1();

        $serviceTitle2 = $promEmail->getServiceTitle2();
        $servicePicture2 = $promEmail->getServicePicture2();
        $serviceContent2 = $promEmail->getServiceContent2();
        $serviceLink2 = $promEmail->getServiceLink2();
        $services = [];
        if ($serviceTitle1 != null) {
            array_push($services, ['title' => $serviceTitle1, 'picture' => $servicePicture1, 'content' => $serviceContent1, 'link' => $serviceLink1]);
        }
        if ($serviceTitle2 != null) {
            array_push($services, ['title' => $serviceTitle2, 'picture' => $servicePicture2, 'content' => $serviceContent2, 'link' => $serviceLink2]);
        }

        $footerPicture = $promEmail->getFooterPicture();
        $footerLink = $promEmail->getFooterPictureLink();

        $body = $this->renderView('site/promotionEmail/promotionEmail.html.twig', [
            'subject' => $subject,
            'home' => $home,
            'primaryPicture' => $primaryPicture,
            'primaryTitle' => $primaryTitle,
            'intros' => $intros,
            'offersTitle' => $offersTitle,
            'offers' => $offersProducts,
            'linkOffers' => $linkOffers,
            'productsTitle' => $productsTitle,
            'products' => $products,
            'linkProducts' => $linkProducts,
            'productsOffers' => $productsOffers,
            'promotion' => $promotion,
            'blogsTitle' => $blogTitle,
            'blogs' => $blogs,
            'servicesTitle' => $servicesTitle,
            'linkServices' => $linkServices,
            'services' => $services,
            'footerPicture' => $footerPicture,
            'footerLink' => $footerLink,
        ]);

        $bodyRender = $this->render('site/promotionEmail/promotionEmail.html.twig', [
            'subject' => $subject,
            'home' => $home,
            'primaryPicture' => $primaryPicture,
            'primaryTitle' => $primaryTitle,
            'intros' => $intros,
            'offersTitle' => $offersTitle,
            'offers' => $offersProducts,
            'productsTitle' => $productsTitle,
            'products' => $products,
            'productsOffers' => $productsOffers,
            'linkProducts' => $linkProducts,
            'promotion' => $promotion,
            'blogsTitle' => $blogTitle,
            'blogs' => $blogs,
            'servicesTitle' => $servicesTitle,
            'linkServices' => $linkServices,
            'services' => $services,
            'footerPicture' => $footerPicture,
            'footerLink' => $footerLink,
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->get('email_service')->send($config->getEmail(), 'Comercial Conceptos', $email, $promEmail->getSubject(), $body);
            }
        }

        return $bodyRender;
    }


    /**
     * @Route(name="preview_welcome_email", path="/previewWelcomeEmail/{id}")
     *
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function previewWelcomeEmail()
    {

        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);

        $email = $this->getDoctrine()->getManager()
                ->getRepository('AppBundle:WelcomeEmail')->findOneBy(['active'=>true]);

        $subject = $email->getSubject();

        $primaryPicture = $email->getPrimaryPicture();
        $primaryTitle = $email->getPrimaryTitle();

        $introTitle1 = $email->getIntroTitle1();
        $introPicture1 = $email->getIntroPicture1();
        $introContent1 = $email->getIntroContent1();
        $introLink1 = $email->getIntroLink1();

        $introTitle2 = $email->getIntroTitle2();
        $introPicture2 = $email->getIntroPicture2();
        $introContent2 = $email->getIntroContent2();
        $introLink2 = $email->getIntroLink2();

        $introTitle3 = $email->getIntroTitle3();
        $introPicture3 = $email->getIntroPicture3();
        $introContent3 = $email->getIntroContent3();
        $introLink3 = $email->getIntroLink3();

        $intros = [];
        if ($introTitle1 != null)
            array_push($intros, ['title' => $introTitle1, 'picture' => $introPicture1, 'content' => $introContent1, 'link' => $introLink1]);
        if ($introTitle2 != null)
            array_push($intros, ['title' => $introTitle2, 'picture' => $introPicture2, 'content' => $introContent2, 'link' => $introLink2]);
        if ($introTitle3 != null)
            array_push($intros, ['title' => $introTitle3, 'picture' => $introPicture3, 'content' => $introContent3, 'link' => $introLink3]);


        $offersTitle = $email->getOffersTitle();
        $offers = $email->getOffers();
        $linkOffers = $email->getLinkOffers();
        $offersProducts_ = [];
        $offersProducts = [];
        if (count($offers) > 0) {

            foreach ($offers as $offer)
                foreach ($offer->getProducts() as $product)
                    $offersProducts_[] = ['product' => $product, 'offerPrice' => $offer->getPrice()];

            $offersProductsIndex = array_rand($offersProducts_, min([count($offersProducts_), 4]));
            if (is_array($offersProductsIndex))
                foreach ($offersProductsIndex as $index)
                    $offersProducts[] = $offersProducts_[$index];
            else
                $offersProducts[] = $offersProducts_[$offersProductsIndex];
        }

        $productsTitle = $email->getProductsTitle();
        $products = $email->getProducts();
        $linkProducts = $email->getLinkProducts();
        $productsOffers = [];

        foreach ($products as $product) {
            $productsOffers[] = count($product->getOffers()) > 0;
        }

        $promotionTitle = $email->getPromotionTitle();
        $promotionPicture = $email->getPromotionPicture();
        $promotionContent = $email->getPromotionContent();
        $promotionLink = $email->getPromotionLink();

        $promotion = ['title' => $promotionTitle,
            'picture' => $promotionPicture,
            'content' => $promotionContent,
            'link' => $promotionLink];

        $blogTitle = $email->getBlogTitle();
        $blogs = $email->getBlogs();

        $servicesTitle = $email->getServicesTitle();
        $linkServices = $email->getLinkServices();

        $serviceTitle1 = $email->getServiceTitle1();
        $servicePicture1 = $email->getServicePicture1();
        $serviceContent1 = $email->getServiceContent1();
        $serviceLink1 = $email->getServiceLink1();

        $serviceTitle2 = $email->getServiceTitle2();
        $servicePicture2 = $email->getServicePicture2();
        $serviceContent2 = $email->getServiceContent2();
        $serviceLink2 = $email->getServiceLink2();
        $services = [];
        if ($serviceTitle1 != null) {
            array_push($services, ['title' => $serviceTitle1, 'picture' => $servicePicture1, 'content' => $serviceContent1, 'link' => $serviceLink1]);
        }
        if ($serviceTitle2 != null) {
            array_push($services, ['title' => $serviceTitle2, 'picture' => $servicePicture2, 'content' => $serviceContent2, 'link' => $serviceLink2]);
        }

        $footerPicture = $email->getFooterPicture();
        $footerLink = $email->getFooterPictureLink();

        return $this->render('site/promotionEmail/promotionEmail.html.twig', [
            'subject' => $subject,
            'home' => $home,
            'primaryPicture' => $primaryPicture,
            'primaryTitle' => $primaryTitle,
            'intros' => $intros,
            'offersTitle' => $offersTitle,
            'offers' => $offersProducts,
            'linkOffers' => $linkOffers,
            'productsTitle' => $productsTitle,
            'products' => $products,
            'linkProducts' => $linkProducts,
            'productsOffers' => $productsOffers,
            'promotion' => $promotion,
            'blogsTitle' => $blogTitle,
            'blogs' => $blogs,
            'servicesTitle' => $servicesTitle,
            'linkServices' => $linkServices,
            'services' => $services,
            'footerPicture' => $footerPicture,
            'footerLink' => $footerLink,
        ]);

    }

    /**
     * @param Request $request
     * @param $id
     *
     */
    public function sendWelcomeEmail($email, $username)
    {
        $home = $this->entityManager->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);

        $email = $this->entityManager->getRepository('AppBundle:WelcomeEmail')->findOneBy(['active'=>true]);
        if ($email == null)
            return;

        $subject = $email->getSubject();

        $primaryPicture = $email->getPrimaryPicture();
        $primaryTitle = $email->getPrimaryTitle();

        $introTitle1 = $email->getIntroTitle1();
        $introPicture1 = $email->getIntroPicture1();
        $introContent1 = $email->getIntroContent1();
        $introLink1 = $email->getIntroLink1();

        $introTitle2 = $email->getIntroTitle2();
        $introPicture2 = $email->getIntroPicture2();
        $introContent2 = $email->getIntroContent2();
        $introLink2 = $email->getIntroLink2();

        $introTitle3 = $email->getIntroTitle3();
        $introPicture3 = $email->getIntroPicture3();
        $introContent3 = $email->getIntroContent3();
        $introLink3 = $email->getIntroLink3();

        $intros = [];
        if ($introTitle1 != null)
            array_push($intros, ['title' => $introTitle1, 'picture' => $introPicture1, 'content' => $introContent1, 'link' => $introLink1]);
        if ($introTitle2 != null)
            array_push($intros, ['title' => $introTitle2, 'picture' => $introPicture2, 'content' => $introContent2, 'link' => $introLink2]);
        if ($introTitle3 != null)
            array_push($intros, ['title' => $introTitle3, 'picture' => $introPicture3, 'content' => $introContent3, 'link' => $introLink3]);


        $offersTitle = $email->getOffersTitle();
        $offers = $email->getOffers();
        $linkOffers = $email->getLinkOffers();
        $offersProducts_ = [];
        $offersProducts = [];
        if (count($offers) > 0) {

            foreach ($offers as $offer)
                foreach ($offer->getProducts() as $product)
                    $offersProducts_[] = ['product' => $product, 'offerPrice' => $offer->getPrice()];

            $offersProductsIndex = array_rand($offersProducts_, min([count($offersProducts_), 4]));
            if (is_array($offersProductsIndex))
                foreach ($offersProductsIndex as $index)
                    $offersProducts[] = $offersProducts_[$index];
            else
                $offersProducts[] = $offersProducts_[$offersProductsIndex];
        }

        $productsTitle = $email->getProductsTitle();
        $products = $email->getProducts();
        $linkProducts = $email->getLinkProducts();
        $productsOffers = [];

        foreach ($products as $product) {
            $productsOffers[] = count($product->getOffers()) > 0;
        }

        $promotionTitle = $email->getPromotionTitle();
        $promotionPicture = $email->getPromotionPicture();
        $promotionContent = $email->getPromotionContent();
        $promotionLink = $email->getPromotionLink();

        $promotion = ['title' => $promotionTitle,
            'picture' => $promotionPicture,
            'content' => $promotionContent,
            'link' => $promotionLink];

        $blogTitle = $email->getBlogTitle();
        $blogs = $email->getBlogs();

        $servicesTitle = $email->getServicesTitle();
        $linkServices = $email->getLinkServices();

        $serviceTitle1 = $email->getServiceTitle1();
        $servicePicture1 = $email->getServicePicture1();
        $serviceContent1 = $email->getServiceContent1();
        $serviceLink1 = $email->getServiceLink1();

        $serviceTitle2 = $email->getServiceTitle2();
        $servicePicture2 = $email->getServicePicture2();
        $serviceContent2 = $email->getServiceContent2();
        $serviceLink2 = $email->getServiceLink2();
        $services = [];
        if ($serviceTitle1 != null) {
            array_push($services, ['title' => $serviceTitle1, 'picture' => $servicePicture1, 'content' => $serviceContent1, 'link' => $serviceLink1]);
        }
        if ($serviceTitle2 != null) {
            array_push($services, ['title' => $serviceTitle2, 'picture' => $servicePicture2, 'content' => $serviceContent2, 'link' => $serviceLink2]);
        }

        $footerPicture = $email->getFooterPicture();
        $footerLink = $email->getFooterPictureLink();

        $body = $this->renderView('site/promotionEmail/promotionEmail.html.twig', [
            'subject' => $subject,
            'home' => $home,
            'primaryPicture' => $primaryPicture,
            'primaryTitle' => $primaryTitle,
            'intros' => $intros,
            'offersTitle' => $offersTitle,
            'offers' => $offersProducts,
            'linkOffers' => $linkOffers,
            'productsTitle' => $productsTitle,
            'products' => $products,
            'linkProducts' => $linkProducts,
            'productsOffers' => $productsOffers,
            'promotion' => $promotion,
            'blogsTitle' => $blogTitle,
            'blogs' => $blogs,
            'servicesTitle' => $servicesTitle,
            'linkServices' => $linkServices,
            'services' => $services,
            'footerPicture' => $footerPicture,
            'footerLink' => $footerLink,
        ]);

        $config = $this->entityManager->getRepository('AppBundle:Configuration')->find(1);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->get('email_service')->send($config->getEmail(), 'Comercial Conceptos', $email, $subject, $body);
        }

    }

}