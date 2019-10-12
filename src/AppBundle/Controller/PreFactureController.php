<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Request\PreFacture;
use AppBundle\Entity\Request\PreFactureProduct;
use AppBundle\Entity\Request\PreFactureCard;
use AppBundle\DTO\PreFactureDTO;
use AppBundle\Form\PreFactureType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PreFactureController extends Controller
{
  /**
   * @Route(name="new_pre_facture", path="/admin/pre-facture/new")
   *
   * @param Request $request
   *
   * @throws \Exception
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function newAction(Request $request)
  {
    $form = $this->createForm(PreFactureType::class, new PreFactureDTO());
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $date = new \DateTime($form->get('date')->getData());

      $clientId = json_decode($form->get('client')->getData(), true)[0];
      $client = $this->getDoctrine()->getRepository('AppBundle:Request\Client')->find($clientId);

      $preFacture = new PreFacture();
      $preFacture->setDate($date);
      $preFacture->setClient($client);
      $preFacture->setFinalPrice($form->get('finalPrice')->getData());
      $preFacture->setTransportCost($form->get('transportCost')->getData());
      $preFacture->setDiscount($form->get('discount')->getData());
      $preFacture->setFirstClientDiscount($form->get('firstClientDiscount')->getData());

      $preFactureProducts = json_decode($form->get('preFactureProducts')->getData(), true);
      if (!is_array($preFactureProducts)) {
        $preFactureProducts = [];
      }
      foreach ($preFactureProducts as $product) {
        $preFactureProduct = new PreFactureProduct();
        $preFactureProduct->setPreFacture($preFacture);
        $preFactureProduct->setProduct($this->getDoctrine()->getRepository('AppBundle:Product')->find($product['product']));
        $preFactureProduct->setCount($product['count']);
        $preFactureProduct->setIsAriplaneForniture($product['airplaneFurniture']);
        $preFactureProduct->setIsAriplaneMattress($product['airplaneMattress']);
        $this->getDoctrine()->getManager()->persist($preFactureProduct);
        $preFacture->addPreFactureProduct($preFactureProduct);
      }

      $preFactureCards = json_decode($form->get('preFactureCards')->getData(), true);
      if (!is_array($preFactureCards)) {
        $preFactureCards = [];
      }
      foreach ($preFactureCards as $card) {
        $preFactureCard = new PreFactureCard();
        $preFactureCard->setPreFacture($preFacture);
        $preFactureCard->setPrice($card['card']);
        $preFactureCard->setCount($card['count']);
        $this->getDoctrine()->getManager()->persist($preFactureCard);
        $preFacture->addPreFactureCard($preFactureCard);
      }

      $factures = json_decode($form->get('factures')->getData(), true);
      if (!is_array($factures)) {
        $factures = [];
      }
      foreach ($factures as $facture) {
        $factureDB = $this->getDoctrine()->getRepository('AppBundle:Request\Facture')->find($facture['id']);
        $factureDB->setPreFacture($preFacture);
      }

      $this->getDoctrine()->getManager()->persist($preFacture);
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('easyadmin', [
        'entity' => 'PreFacture',
        'action' => 'list',
      ]);
    }
    return $this->render('::new_edit_pre_facture.html.twig', [
      'action' => 'new',
      'clients' => $this->getDoctrine()->getRepository('AppBundle:Request\Client')->findAll(),
      'products' => $this->getDoctrine()->getRepository('AppBundle:Product')->findAll(),
      'factures' => $this->getDoctrine()->getRepository('AppBundle:Request\Facture')->findAll(),
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route(name="edit_pre_facture", path="/admin/pre-facture/edit/{id}")
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
    $preFactureDB = $this->getDoctrine()->getRepository('AppBundle:Request\PreFacture')->find($id);
    $dto = new PreFactureDTO();
    $dto->setDate(json_encode($preFactureDB->getDate()));
    $dto->setClient($preFactureDB->getClient()->getId());
    $dto->setFinalPrice($preFactureDB->getFinalPrice());
    $dto->setTransportCost($preFactureDB->getTransportCost());
    $dto->setDiscount($preFactureDB->getDiscount());
    $dto->setFirstClientDiscount($preFactureDB->getFirstClientDiscount());

    $preFactureProducts = [];
    foreach ($preFactureDB->getPreFactureProducts() as $preFactureProduct) {
      $preFactureProducts[] = [
        'id' => $preFactureProduct->getId(),
        'product' => $preFactureProduct->getProduct()->getId(),
        'code' => $preFactureProduct->getProduct()->getCode(),
        'image' => $preFactureProduct->getProduct()->getMainImage()->getImage(),
        'count' => $preFactureProduct->getCount(),
        'airplaneFurniture' => $preFactureProduct->getIsAriplaneForniture(),
        'airplaneMattress' => $preFactureProduct->getIsAriplaneMattress(),
      ];
    }
    $dto->setPreFactureProducts(json_encode($preFactureProducts));

    $preFactureCards = [];
    foreach ($preFactureDB->getPreFactureCards() as $preFactureCard) {
      $preFactureCards[] = [
        'id' => $preFactureCard->getId(),
        'price' => $preFactureCard->getPrice(),
        'count' => $preFactureCard->getCount(),
      ];
    }
    $dto->setPreFactureCards(json_encode($preFactureCards));

    $factures = [];
    foreach ($preFactureDB->getFactures() as $facture) {
      $factures[] = [
        'id' => $facture->getId(),
        'date' => date_format($facture->getDate(), 'Y'),
      ];
    }
    $dto->setFactures(json_encode($factures));

    $form = $this->createForm(PreFactureType::class, $dto);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $date = new \DateTime($form->get('date')->getData());

      $clientId = json_decode($form->get('client')->getData(), true)[0];
      $client = $this->getDoctrine()->getRepository('AppBundle:Request\Client')->find($clientId);

      $preFactureDB = $this->getDoctrine()->getRepository('AppBundle:Request\PreFacture')->find($id);
      $preFactureDB->setDate($date);
      $preFactureDB->setClient($client);
      $preFactureDB->setFinalPrice($form->get('finalPrice')->getData());
      $preFactureDB->setTransportCost($form->get('transportCost')->getData());
      $preFactureDB->setDiscount($form->get('discount')->getData());
      $preFactureDB->setFirstClientDiscount($form->get('firstClientDiscount')->getData());

      foreach ($preFactureDB->getPreFactureProducts() as $preFactureProduct) {
        $preFactureProductDB = $this->getDoctrine()->getRepository('AppBundle:Request\PreFactureProduct')->find($preFactureProduct->getId());
        $this->getDoctrine()->getManager()->remove($preFactureProductDB);
      }
      $preFactureDB->getPreFactureProducts()->clear();
      $preFactureProducts = json_decode($form->get('preFactureProducts')->getData(), true);
      if (!is_array($preFactureProducts)) {
        $preFactureProducts = [];
      }
      foreach ($preFactureProducts as $product) {
        $preFactureProduct = new PreFactureProduct();
        $preFactureProduct->setPreFacture($preFactureDB);
        $preFactureProduct->setProduct($this->getDoctrine()->getRepository('AppBundle:Product')->find($product['product']));
        $preFactureProduct->setCount($product['count']);
        $preFactureProduct->setIsAriplaneForniture($product['airplaneFurniture']);
        $preFactureProduct->setIsAriplaneMattress($product['airplaneMattress']);
        $this->getDoctrine()->getManager()->persist($preFactureProduct);
        $preFactureDB->addPreFactureProduct($preFactureProduct);
      }

      foreach ($preFactureDB->getPreFactureCards() as $preFactureCard) {
        $preFactureCardDB = $this->getDoctrine()->getRepository('AppBundle:Request\PreFactureCard')->find($preFactureCard->getId());
        $this->getDoctrine()->getManager()->remove($preFactureCardDB);
      }
      $preFactureDB->getPreFactureCards()->clear();
      $preFactureCards = json_decode($form->get('preFactureCards')->getData(), true);
      if (!is_array($preFactureCards)) {
        $preFactureCards = [];
      }
      foreach ($preFactureCards as $card) {
        $preFactureCard = new PreFactureCard();
        $preFactureCard->setPreFacture($preFactureDB);
        $preFactureCard->setPrice($card['card']);
        $preFactureCard->setCount($card['count']);
        $this->getDoctrine()->getManager()->persist($preFactureCard);
        $preFactureDB->addPreFactureCard($preFactureCard);
      }

      foreach ($preFactureDB->getFactures() as $facture) {
        $facture->setPreFacture(null);
      }
      $preFactureDB->getFactures()->clear();
      $factures = json_decode($form->get('factures')->getData(), true);
      if (!is_array($factures)) {
        $factures = [];
      }
      foreach ($factures as $facture) {
        $factureDB = $this->getDoctrine()->getRepository('AppBundle:Request\Facture')->find($facture['id']);
        $factureDB->setPreFacture($preFactureDB);
      }

      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('easyadmin', [
        'entity' => 'PreFacture',
        'action' => 'list',
      ]);
    }
    return $this->render('::new_edit_pre_facture.html.twig', [
      'action' => 'edit',
      'clients' => $this->getDoctrine()->getRepository('AppBundle:Request\Client')->findAll(),
      'products' => $this->getDoctrine()->getRepository('AppBundle:Product')->findAll(),
      'factures' => $this->getDoctrine()->getRepository('AppBundle:Request\Facture')->findAll(),
      'form' => $form->createView(),
    ]);
  }
}
