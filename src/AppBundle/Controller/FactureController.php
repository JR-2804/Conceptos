<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Request\Facture;
use AppBundle\Entity\Request\FactureProduct;
use AppBundle\Entity\Request\FactureCard;
use AppBundle\DTO\FactureDTO;
use AppBundle\Form\FactureType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FactureController extends Controller
{
  /**
   * @Route(name="new_facture", path="/admin/facture/new")
   *
   * @param Request $request
   *
   * @throws \Exception
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function newAction(Request $request)
  {
    $form = $this->createForm(FactureType::class, new FactureDTO());
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $date = new \DateTime($form->get('date')->getData());

      $clientId = json_decode($form->get('client')->getData(), true)[0];
      $client = $this->getDoctrine()->getRepository('AppBundle:Request\Client')->find($clientId);

      $facture = new Facture();
      $facture->setDate($date);
      $facture->setClient($client);
      $facture->setFinalPrice($form->get('finalPrice')->getData());
      $facture->setTransportCost($form->get('transportCost')->getData());
      $facture->setDiscount($form->get('discount')->getData());
      $facture->setFirstClientDiscount($form->get('firstClientDiscount')->getData());

      $factureProducts = json_decode($form->get('factureProducts')->getData(), true);
      if (!is_array($factureProducts)) {
        $factureProducts = [];
      }
      foreach ($factureProducts as $product) {
        $factureProduct = new FactureProduct();
        $factureProduct->setFacture($facture);
        $factureProduct->setProduct($this->getDoctrine()->getRepository('AppBundle:Product')->find($product['product']));
        $factureProduct->setCount($product['count']);
        $factureProduct->setIsAriplaneForniture($product['airplaneFurniture']);
        $factureProduct->setIsAriplaneMattress($product['airplaneMattress']);
        $this->getDoctrine()->getManager()->persist($factureProduct);
        $facture->addFactureProduct($factureProduct);
      }

      $factureCards = json_decode($form->get('factureCards')->getData(), true);
      if (!is_array($factureCards)) {
        $factureCards = [];
      }
      foreach ($factureCards as $card) {
        $factureCard = new FactureCard();
        $factureCard->setFacture($facture);
        $factureCard->setPrice($card['card']);
        $factureCard->setCount($card['count']);
        $this->getDoctrine()->getManager()->persist($factureCard);
        $facture->addFactureCard($factureCard);
      }

      $this->getDoctrine()->getManager()->persist($facture);
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('easyadmin', [
        'entity' => 'Facture',
        'action' => 'list',
      ]);
    }
    return $this->render('::new_edit_facture.html.twig', [
      'action' => 'new',
      'clients' => $this->getDoctrine()->getRepository('AppBundle:Request\Client')->findAll(),
      'products' => $this->getDoctrine()->getRepository('AppBundle:Product')->findAll(),
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route(name="edit_facture", path="/admin/facture/edit/{id}")
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
    $factureDB = $this->getDoctrine()->getRepository('AppBundle:Request\Facture')->find($id);
    $dto = new FactureDTO();
    $dto->setDate(json_encode($factureDB->getDate()));
    $dto->setClient($factureDB->getClient()->getId());
    $dto->setFinalPrice($factureDB->getFinalPrice());
    $dto->setTransportCost($factureDB->getTransportCost());
    $dto->setDiscount($factureDB->getDiscount());
    $dto->setFirstClientDiscount($factureDB->getFirstClientDiscount());

    $factureProducts = [];
    foreach ($factureDB->getFactureProducts() as $factureProduct) {
      $newfactureProduct = [
        'id' => $factureProduct->getId(),
        'product' => $factureProduct->getProduct()->getId(),
        'code' => $factureProduct->getProduct()->getCode(),
        'image' => $factureProduct->getProduct()->getMainImage()->getImage(),
        'price' => $factureProduct->getProduct()->getPrice(),
        'count' => $factureProduct->getCount(),
        'airplaneFurniture' => $factureProduct->getIsAriplaneForniture(),
        'airplaneMattress' => $factureProduct->getIsAriplaneMattress(),
      ];
      if ($factureProduct->getOffer()) {
        $newfactureProduct["offerPrice"] = $factureProduct->getOffer()->getPrice();
      }
      $factureProducts[] = $newfactureProduct;
    }
    $dto->setFactureProducts(json_encode($factureProducts));

    $factureCards = [];
    foreach ($factureDB->getFactureCards() as $factureCard) {
      $factureCards[] = [
        'id' => $factureCard->getId(),
        'price' => $factureCard->getPrice(),
        'count' => $factureCard->getCount(),
      ];
    }
    $dto->setFactureCards(json_encode($factureCards));

    $form = $this->createForm(FactureType::class, $dto);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $date = new \DateTime($form->get('date')->getData());

      $clientId = json_decode($form->get('client')->getData(), true)[0];
      $client = $this->getDoctrine()->getRepository('AppBundle:Request\Client')->find($clientId);

      $factureDB = $this->getDoctrine()->getRepository('AppBundle:Request\Facture')->find($id);
      $factureDB->setDate($date);
      $factureDB->setClient($client);
      $factureDB->setFinalPrice($form->get('finalPrice')->getData());
      $factureDB->setTransportCost($form->get('transportCost')->getData());
      $factureDB->setDiscount($form->get('discount')->getData());
      $factureDB->setFirstClientDiscount($form->get('firstClientDiscount')->getData());

      foreach ($factureDB->getFactureProducts() as $factureProduct) {
        $factureProductDB = $this->getDoctrine()->getRepository('AppBundle:Request\FactureProduct')->find($factureProduct->getId());
        $this->getDoctrine()->getManager()->remove($factureProductDB);
      }
      $factureDB->getFactureProducts()->clear();
      $factureProducts = json_decode($form->get('factureProducts')->getData(), true);
      if (!is_array($factureProducts)) {
        $factureProducts = [];
      }
      foreach ($factureProducts as $product) {
        $factureProduct = new FactureProduct();
        $factureProduct->setFacture($factureDB);
        $factureProduct->setProduct($this->getDoctrine()->getRepository('AppBundle:Product')->find($product['product']));
        $factureProduct->setCount($product['count']);
        $factureProduct->setIsAriplaneForniture($product['airplaneFurniture']);
        $factureProduct->setIsAriplaneMattress($product['airplaneMattress']);
        $this->getDoctrine()->getManager()->persist($factureProduct);
        $factureDB->addFactureProduct($factureProduct);
      }

      foreach ($factureDB->getFactureCards() as $factureCard) {
        $factureCardDB = $this->getDoctrine()->getRepository('AppBundle:Request\FactureCard')->find($factureCard->getId());
        $this->getDoctrine()->getManager()->remove($factureCardDB);
      }
      $factureDB->getFactureCards()->clear();
      $factureCards = json_decode($form->get('factureCards')->getData(), true);
      if (!is_array($factureCards)) {
        $factureCards = [];
      }
      foreach ($factureCards as $card) {
        $factureCard = new FactureCard();
        $factureCard->setFacture($factureDB);
        $factureCard->setPrice($card['card']);
        $factureCard->setCount($card['count']);
        $this->getDoctrine()->getManager()->persist($factureCard);
        $factureDB->addFactureCard($factureCard);
      }

      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('easyadmin', [
        'entity' => 'Facture',
        'action' => 'list',
      ]);
    }
    return $this->render('::new_edit_facture.html.twig', [
      'action' => 'edit',
      'clients' => $this->getDoctrine()->getRepository('AppBundle:Request\Client')->findAll(),
      'products' => $this->getDoctrine()->getRepository('AppBundle:Product')->findAll(),
      'form' => $form->createView(),
    ]);
  }
}
