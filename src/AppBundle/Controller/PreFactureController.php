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
      'prefactureId' => $id,
      'action' => 'edit',
      'clients' => $this->getDoctrine()->getRepository('AppBundle:Request\Client')->findAll(),
      'products' => $this->getDoctrine()->getRepository('AppBundle:Product')->findAll(),
      'factures' => $this->getDoctrine()->getRepository('AppBundle:Request\Facture')->findAll(),
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route(name="prefacture_facture_data", path="/admin/pre-facture/facture-data/{id}")
   *
   * @param Request $request
   * @param $id
   *
   * @return JsonResponse
   *
   * @internal param Request $request
   */
  public function getPreFactureFactureDataAction(Request $request, $id)
  {
    $prefacture = $this->getDoctrine()->getRepository('AppBundle:Request\PreFacture')->find($id);
    $factures = $prefacture->getFactures();

    $productsData = [];
    foreach ($prefacture->getPreFactureProducts() as $product) {
      $productId = $product->getProduct()->getId();
      $productCount = $product->getCount();

      $productsData[$productId] = [
        "data" => [
          "id" => $productId,
          "code" => $product->getProduct()->getCode(),
          "image" => $product->getProduct()->getMainImage()->getImage(),
          "count" => $product->getCount(),
          "isAriplaneForniture" => $product->getIsAriplaneForniture(),
          "isAriplaneMattress" => $product->getIsAriplaneMattress(),
          "preFactureProductId" => $product->getId(),
        ],
        "prefacture" => 0,
        "factures" => [],
      ];
      if ($product->getOffer()) {
        $productsData[$productId]["data"]["offerId"] = $product->getOffer()->getId();
      }

      foreach ($factures as $facture) {
        $exists = false;
        foreach ($facture->getFactureProducts() as $factureProduct) {
          if ($factureProduct->getId() == $productId) {
            $productsData[$productId]["factures"][$facture->getId()."-".date_format($facture->getDate(), 'Y')] = 0;
            $exists = true;
          }
        }
        if (!$exists) {
          $productsData[$productId]["factures"][$facture->getId()."-".date_format($facture->getDate(), 'Y')] = 0;
        }
      }
      $productsData[$productId]["prefacture"] = $productCount;
    }

    $cardsData = [];
    foreach ($prefacture->getPreFactureCards() as $card) {
      $cardPrice = $card->getPrice();
      $cardCount = $card->getCount();
      $cardsData[$cardPrice] = [
        "data" => [
          "price" => $cardPrice,
          "count" => $cardCount,
          "prefactureCardId" => $card->getId(),
        ],
        "prefacture" => 0,
        "factures" => [],
      ];

      foreach ($factures as $facture) {
        $exists = false;
        foreach ($facture->getFactureCards() as $factureCard) {
          if ($factureCard->getPrice() == $cardPrice) {
            $cardsData[$cardPrice]["factures"][$facture->getId()."-".date_format($facture->getDate(), 'Y')] = 0;
            $exists = true;
          }
        }
        if (!$exists) {
          $cardsData[$cardPrice]["factures"][$facture->getId()."-".date_format($facture->getDate(), 'Y')] = 0;
        }
      }
      $cardsData[$cardPrice]["prefacture"] = $cardCount;
    }

    return new JsonResponse([
      'productsData' => json_encode($productsData),
      'cardsData' => json_encode($cardsData),
    ]);
  }

  /**
   * @Route(name="set_prefacture_facture_data", path="/admin/pre-facture/set-facture-data/{id}")
   *
   * @param Request $request
   * @param $id
   *
   * @return JsonResponse
   *
   * @internal param Request $request
   */
  public function setPreFactureFactureDataAction(Request $request, $id)
  {
    $productsData = json_decode($request->request->get('productsData'), true);
    $cardsData = json_decode($request->request->get('cardsData'), true);
    $prefacture = $this->getDoctrine()->getRepository('AppBundle:Request\PreFacture')->find($id);
    $factures = $prefacture->getFactures();

    foreach ($productsData as $productData) {
      $productId = $productData["data"]["id"];

      foreach ($factures as $facture) {
        $newCount = $productsData[$productId]["factures"][$facture->getId()."-".date_format($facture->getDate(), 'Y')];
        if ($newCount > 0) {
          $exists = false;
          foreach ($facture->getFactureProducts() as $factureProduct) {
            if ($factureProduct->getProduct()->getId() == $productId) {
              $factureProduct->setCount($factureProduct->getCount() + $newCount);
              $exists = true;
            }
          }
          if (!$exists) {
            $factureProduct = new FactureProduct();
            $factureProduct->setFacture($facture);
            $factureProduct->setProduct($this->getDoctrine()->getRepository('AppBundle:Product')->find($productId));
            $factureProduct->setCount($newCount);
            $factureProduct->setIsAriplaneForniture($productsData[$productId]["data"]["isAriplaneForniture"]);
            $factureProduct->setIsAriplaneMattress($productsData[$productId]["data"]["isAriplaneMattress"]);

            if (array_key_exists("offerId", $productsData[$productId]["data"])) {
              $factureProduct->setOffer($this->getDoctrine()->getRepository('AppBundle:Offer')->find($productsData[$productId]["data"]["offerId"]));
            }

            $this->getDoctrine()->getManager()->persist($factureProduct);
            $facture->addFactureProduct($factureProduct);
          }

          $preFactureProduct = $this->getDoctrine()->getRepository('AppBundle:Request\PreFactureProduct')->find($productsData[$productId]["data"]["preFactureProductId"]);
          $preFactureProduct->setCount($preFactureProduct->getCount() - $newCount);
        }
      }
    }

    foreach ($cardsData as $cardData) {
      $cardPrice = $cardData["data"]["price"];

      foreach ($factures as $facture) {
        $newCount = $cardsData[$cardPrice]["factures"][$facture->getId()."-".date_format($facture->getDate(), 'Y')];
        if ($newCount > 0) {
          $exists = false;
          foreach ($facture->getFactureCards() as $factureCard) {
            if ($factureCard->getPrice() == $cardPrice) {
              $factureCard->setCount($factureCard->getCount() + $newCount);
              $exists = true;
            }
          }
          if (!$exists) {
            $factureCard = new FactureCard();
            $factureCard->setFacture($facture);
            $factureCard->setPrice($cardPrice);
            $factureCard->setCount($newCount);

            $this->getDoctrine()->getManager()->persist($factureCard);
            $facture->addFactureCard($factureCard);
          }

          $preFactureCard = $this->getDoctrine()->getRepository('AppBundle:Request\PreFactureCard')->find($cardsData[$cardPrice]["data"]["prefactureCardId"]);
          $preFactureCard->setCount($preFactureCard->getCount() - $newCount);
        }
      }
    }

    $this->getDoctrine()->getManager()->flush();

    return new JsonResponse();
  }
}
