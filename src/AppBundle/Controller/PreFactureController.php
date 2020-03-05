<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Request\PreFacture;
use AppBundle\Entity\Request\PreFactureProduct;
use AppBundle\Entity\Request\PreFactureCard;
use AppBundle\DTO\PreFactureDTO;
use AppBundle\Form\PreFactureType;
use AppBundle\Entity\Request\FactureProduct;
use AppBundle\Entity\Request\FactureCard;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PreFactureController extends Controller
{
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

    $preFactureProducts = [];
    foreach ($preFactureDB->getPreFactureProducts() as $preFactureProduct) {
      $newPreFactureProduct = [
        'id' => $preFactureProduct->getId(),
        'image' => $preFactureProduct->getProduct()->getMainImage()->getImage(),
        'code' => $preFactureProduct->getProduct()->getCode(),
        'count' => $preFactureProduct->getCount(),
        'price' => $preFactureProduct->getProduct()->getPrice(),
      ];
      $preFactureProducts[] = $newPreFactureProduct;
    }
    $dto->setPreFactureProducts(json_encode($preFactureProducts));

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
        if (array_key_exists('offerId', $product)) {
          $preFactureProduct->setOffer($this->getDoctrine()->getRepository('AppBundle:Offer')->find($product['offerId']));
        }

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

      $preFactureDB->calculatePrice($this->get('product_service'));
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('easyadmin', [
        'entity' => 'PreFacture',
        'action' => 'list',
      ]);
    }

    return $this->render('::edit_pre_facture.html.twig', [
      'form' => $form->createView(),
    ]);
  }
}
