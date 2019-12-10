<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Request\Request as RequestEntity;
use AppBundle\Entity\Request\PreFactureProduct;
use AppBundle\Entity\Request\PreFactureCard;
use AppBundle\Entity\Request\FactureProduct;
use AppBundle\Entity\Request\FactureCard;
use AppBundle\Entity\Request\RequestProduct;
use AppBundle\Entity\Request\RequestCard;
use AppBundle\DTO\RequestDTO;
use AppBundle\Form\RequestType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RequestController extends Controller
{
  /**
   * @Route(name="new_request", path="/admin/request/new")
   *
   * @param Request $request
   *
   * @throws \Exception
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function newAction(Request $request)
  {
    $form = $this->createForm(RequestType::class, new RequestDTO());
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $date = new \DateTime($form->get('date')->getData());

      $clientId = json_decode($form->get('client')->getData(), true)[0];
      $client = $this->getDoctrine()->getRepository('AppBundle:Request\Client')->find($clientId);

      $request = new RequestEntity();
      $request->setDate($date);
      $request->setClient($client);
      $request->setTransportCost($form->get('transportCost')->getData());

      $requestProducts = json_decode($form->get('requestProducts')->getData(), true);
      if (!is_array($requestProducts)) {
        $requestProducts = [];
      }
      foreach ($requestProducts as $product) {
        $requestProduct = new RequestProduct();
        $requestProduct->setRequest($request);
        $requestProduct->setProduct($this->getDoctrine()->getRepository('AppBundle:Product')->find($product['product']));
        $requestProduct->setProductPrice($product['price']);
        $requestProduct->setCount($product['count']);
        $requestProduct->setIsAriplaneForniture($product['airplaneFurniture']);
        $requestProduct->setIsAriplaneMattress($product['airplaneMattress']);
        $this->getDoctrine()->getManager()->persist($requestProduct);
        $request->addRequestProduct($requestProduct);
      }

      $requestCards = json_decode($form->get('requestCards')->getData(), true);
      if (!is_array($requestCards)) {
        $requestCards = [];
      }
      foreach ($requestCards as $card) {
        $requestCard = new RequestCard();
        $requestCard->setRequest($request);
        $requestCard->setPrice($card['card']);
        $requestCard->setCount($card['count']);
        $this->getDoctrine()->getManager()->persist($requestCard);
        $request->addRequestCard($requestCard);
      }

      $preFactures = json_decode($form->get('preFactures')->getData(), true);
      if (!is_array($preFactures)) {
        $preFactures = [];
      }
      foreach ($preFactures as $preFacture) {
        $preFactureDB = $this->getDoctrine()->getRepository('AppBundle:Request\PreFacture')->find($preFacture['id']);
        $preFactureDB->setRequest($request);
      }

      $factures = json_decode($form->get('factures')->getData(), true);
      if (!is_array($factures)) {
        $factures = [];
      }
      foreach ($factures as $facture) {
        $factureDB = $this->getDoctrine()->getRepository('AppBundle:Request\Facture')->find($facture['id']);
        $factureDB->setRequest($request);
      }

      $request->calculatePrice($this->get('product_service'));
      $this->getDoctrine()->getManager()->persist($request);
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('easyadmin', [
        'entity' => 'Request',
        'action' => 'list',
      ]);
    }
    return $this->render('::new_edit_request.html.twig', [
      'action' => 'new',
      'clients' => $this->getDoctrine()->getRepository('AppBundle:Request\Client')->findAll(),
      'products' => $this->getDoctrine()->getRepository('AppBundle:Product')->findAll(),
      'factures' => $this->getDoctrine()->getRepository('AppBundle:Request\Facture')->findAll(),
      'prefactures' => $this->getDoctrine()->getRepository('AppBundle:Request\PreFacture')->findAll(),
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route(name="edit_request", path="/admin/request/edit/{id}")
   *
   * @param Request $request
   * @param $id
   *
   * @throws \Exception
   *
   * @return Response
   */
  public function editAction(Request $request, $id)
  {
    $requestDB = $this->getDoctrine()->getRepository('AppBundle:Request\Request')->find($id);
    $dto = new RequestDTO();
    $dto->setDate(json_encode($requestDB->getDate()));
    $dto->setClient($requestDB->getClient()->getId());
    $dto->setFinalPrice($requestDB->getFinalPrice());
    $dto->setTransportCost($requestDB->getTransportCost());
    $dto->setDiscount($requestDB->getDiscount());
    $dto->setFirstClientDiscount($requestDB->getFirstClientDiscount());

    $requestProducts = [];
    foreach ($requestDB->getRequestProducts() as $requestProduct) {
      $newRequestProduct = [
        'id' => $requestProduct->getId(),
        'product' => $requestProduct->getProduct()->getId(),
        'code' => $requestProduct->getProduct()->getCode(),
        'image' => $requestProduct->getProduct()->getMainImage()->getImage(),
        'price' => $requestProduct->getProductPrice(),
        'state' => $requestProduct->getState(),
        'count' => $requestProduct->getCount(),
        'airplaneFurniture' => $requestProduct->getIsAriplaneForniture(),
        'airplaneMattress' => $requestProduct->getIsAriplaneMattress(),
      ];
      if ($requestProduct->getOffer()) {
        $newRequestProduct["offerId"] = $requestProduct->getOffer()->getId();
        $newRequestProduct["offerPrice"] = $requestProduct->getOffer()->getPrice();
      }
      $requestProducts[] = $newRequestProduct;
    }
    $dto->setRequestProducts(json_encode($requestProducts));

    $requestCards = [];
    foreach ($requestDB->getRequestCards() as $requestCard) {
      $requestCards[] = [
        'id' => $requestCard->getId(),
        'price' => $requestCard->getPrice(),
        'count' => $requestCard->getCount(),
      ];
    }
    $dto->setRequestCards(json_encode($requestCards));

    $form = $this->createForm(RequestType::class, $dto);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $date = new \DateTime($form->get('date')->getData());

      $clientId = json_decode($form->get('client')->getData(), true)[0];
      $client = $this->getDoctrine()->getRepository('AppBundle:Request\Client')->find($clientId);

      $requestDB = $this->getDoctrine()->getRepository('AppBundle:Request\Request')->find($id);
      $requestDB->setDate($date);
      $requestDB->setClient($client);
      $requestDB->setFinalPrice($form->get('finalPrice')->getData());
      $requestDB->setTransportCost($form->get('transportCost')->getData());
      $requestDB->setDiscount($form->get('discount')->getData());
      $requestDB->setFirstClientDiscount($form->get('firstClientDiscount')->getData());

      foreach ($requestDB->getRequestProducts() as $requestProduct) {
        $requestProductDB = $this->getDoctrine()->getRepository('AppBundle:Request\RequestProduct')->find($requestProduct->getId());
        $this->getDoctrine()->getManager()->remove($requestProductDB);
      }
      $requestDB->getRequestProducts()->clear();
      $requestProducts = json_decode($form->get('requestProducts')->getData(), true);
      if (!is_array($requestProducts)) {
        $requestProducts = [];
      }
      foreach ($requestProducts as $product) {
        $requestProduct = new RequestProduct();
        $requestProduct->setRequest($requestDB);
        $requestProduct->setProduct($this->getDoctrine()->getRepository('AppBundle:Product')->find($product['product']));
        $requestProduct->setCount($product['count']);
        $requestProduct->setProductPrice($product['price']);
        $requestProduct->setState($product['state']);
        $requestProduct->setIsAriplaneForniture($product['airplaneFurniture']);
        $requestProduct->setIsAriplaneMattress($product['airplaneMattress']);
        if (array_key_exists('offerId', $product)) {
          $requestProduct->setOffer($this->getDoctrine()->getRepository('AppBundle:Offer')->find($product['offerId']));
        }

        $this->getDoctrine()->getManager()->persist($requestProduct);
        $requestDB->addRequestProduct($requestProduct);
      }

      foreach ($requestDB->getRequestCards() as $requestCard) {
        $requestCardDB = $this->getDoctrine()->getRepository('AppBundle:Request\RequestCard')->find($requestCard->getId());
        $this->getDoctrine()->getManager()->remove($requestCardDB);
      }
      $requestDB->getRequestCards()->clear();
      $requestCards = json_decode($form->get('requestCards')->getData(), true);
      if (!is_array($requestCards)) {
        $requestCards = [];
      }
      foreach ($requestCards as $card) {
        $requestCard = new RequestCard();
        $requestCard->setRequest($requestDB);
        $requestCard->setPrice($card['card']);
        $requestCard->setCount($card['count']);
        $this->getDoctrine()->getManager()->persist($requestCard);
        $requestDB->addRequestCard($requestCard);
      }

      $requestDB->calculatePrice($this->get('product_service'));
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('easyadmin', [
        'entity' => 'Request',
        'action' => 'list',
      ]);
    }
    return $this->render('::new_edit_request.html.twig', [
      'requestId' => $id,
      'memberNumber' => $requestDB->getClient()->getMemberNumber(),
      'firstClient' => $requestDB->getClient()->getRequests()->count() == 1,
      'action' => 'edit',
      'clients' => $this->getDoctrine()->getRepository('AppBundle:Request\Client')->findAll(),
      'products' => $this->getDoctrine()->getRepository('AppBundle:Product')->findAll(),
      'form' => $form->createView(),
    ]);
  }
}
