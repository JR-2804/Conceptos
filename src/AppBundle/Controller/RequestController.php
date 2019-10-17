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
      $request->setFinalPrice($form->get('finalPrice')->getData());
      $request->setTransportCost($form->get('transportCost')->getData());
      $request->setDiscount($form->get('discount')->getData());
      $request->setFirstClientDiscount($form->get('firstClientDiscount')->getData());

      $requestProducts = json_decode($form->get('requestProducts')->getData(), true);
      if (!is_array($requestProducts)) {
        $requestProducts = [];
      }
      foreach ($requestProducts as $product) {
        $requestProduct = new RequestProduct();
        $requestProduct->setRequest($request);
        $requestProduct->setProduct($this->getDoctrine()->getRepository('AppBundle:Product')->find($product['product']));
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
        'price' => $requestProduct->getProduct()->getPrice(),
        'count' => $requestProduct->getCount(),
        'airplaneFurniture' => $requestProduct->getIsAriplaneForniture(),
        'airplaneMattress' => $requestProduct->getIsAriplaneMattress(),
      ];
      if ($requestProduct->getOffer()) {
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

    $preFactures = [];
    foreach ($requestDB->getPreFactures() as $preFacture) {
      $preFactures[] = [
        'id' => $preFacture->getId(),
        'date' => date_format($preFacture->getDate(), 'Y'),
      ];
    }
    $dto->setPreFactures(json_encode($preFactures));

    $factures = [];
    foreach ($requestDB->getFactures() as $facture) {
      $factures[] = [
        'id' => $facture->getId(),
        'date' => date_format($facture->getDate(), 'Y'),
      ];
    }
    $dto->setFactures(json_encode($factures));

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
        $requestProduct->setIsAriplaneForniture($product['airplaneFurniture']);
        $requestProduct->setIsAriplaneMattress($product['airplaneMattress']);
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

      foreach ($requestDB->getPreFactures() as $preFacture) {
        $preFacture->setRequest(null);
      }
      $requestDB->getPreFactures()->clear();
      $preFactures = json_decode($form->get('preFactures')->getData(), true);
      if (!is_array($preFactures)) {
        $preFactures = [];
      }
      foreach ($preFactures as $preFacture) {
        $preFactureDB = $this->getDoctrine()->getRepository('AppBundle:Request\PreFacture')->find($preFacture['id']);
        $preFactureDB->setRequest($requestDB);
      }

      foreach ($requestDB->getFactures() as $facture) {
        $facture->setRequest(null);
      }
      $requestDB->getFactures()->clear();
      $factures = json_decode($form->get('factures')->getData(), true);
      if (!is_array($factures)) {
        $factures = [];
      }
      foreach ($factures as $facture) {
        $factureDB = $this->getDoctrine()->getRepository('AppBundle:Request\Facture')->find($facture['id']);
        $factureDB->setRequest($requestDB);
      }

      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('easyadmin', [
        'entity' => 'Request',
        'action' => 'list',
      ]);
    }
    return $this->render('::new_edit_request.html.twig', [
      'requestId' => $id,
      'action' => 'edit',
      'clients' => $this->getDoctrine()->getRepository('AppBundle:Request\Client')->findAll(),
      'products' => $this->getDoctrine()->getRepository('AppBundle:Product')->findAll(),
      'factures' => $this->getDoctrine()->getRepository('AppBundle:Request\Facture')->findAll(),
      'prefactures' => $this->getDoctrine()->getRepository('AppBundle:Request\PreFacture')->findAll(),
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route(name="request_facture_data", path="/admin/request/facture-data/{id}")
   *
   * @param Request $request
   * @param $id
   *
   * @return JsonResponse
   *
   * @internal param Request $request
   */
  public function getRequestFactureDataAction(Request $request, $id)
  {
    $request = $this->getDoctrine()->getRepository('AppBundle:Request\Request')->find($id);
    $preFactures = $request->getPreFactures();
    $factures = $request->getFactures();

    $productsData = [];
    foreach ($request->getRequestProducts() as $product) {
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
          "requestProductId" => $product->getId(),
        ],
        "request" => 0,
        "prefactures" => [],
        "factures" => [],
      ];
      if ($product->getOffer()) {
        $productsData[$productId]["data"]["offerId"] = $product->getOffer()->getId();
      }

      foreach ($preFactures as $preFacture) {
        $exists = false;
        foreach ($preFacture->getPreFactureProducts() as $preFactureProduct) {
          if ($preFactureProduct->getId() == $productId) {
            $productsData[$productId]["prefactures"][$preFacture->getId()."-".date_format($preFacture->getDate(), 'Y')] = 0;
            $exists = true;
          }
        }
        if (!$exists) {
          $productsData[$productId]["prefactures"][$preFacture->getId()."-".date_format($preFacture->getDate(), 'Y')] = 0;
        }
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
      $productsData[$productId]["request"] = $productCount;
    }

    $cardsData = [];
    foreach ($request->getRequestCards() as $card) {
      $cardPrice = $card->getPrice();
      $cardCount = $card->getCount();
      $cardsData[$cardPrice] = [
        "data" => [
          "price" => $cardPrice,
          "count" => $cardCount,
          "requestCardId" => $card->getId(),
        ],
        "request" => 0,
        "prefactures" => [],
        "factures" => [],
      ];

      foreach ($preFactures as $preFacture) {
        $exists = false;
        foreach ($preFacture->getPreFactureCards() as $preFactureCard) {
          if ($preFactureCard->getPrice() == $cardPrice) {
            $cardsData[$cardPrice]["prefactures"][$preFacture->getId()."-".date_format($preFacture->getDate(), 'Y')] = 0;
            $exists = true;
          }
        }
        if (!$exists) {
          $cardsData[$cardPrice]["prefactures"][$preFacture->getId()."-".date_format($preFacture->getDate(), 'Y')] = 0;
        }
      }
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
      $cardsData[$cardPrice]["request"] = $cardCount;
    }

    return new JsonResponse([
      'productsData' => json_encode($productsData),
      'cardsData' => json_encode($cardsData),
    ]);
  }

  /**
   * @Route(name="set_request_facture_data", path="/admin/request/set-facture-data/{id}")
   *
   * @param Request $request
   * @param $id
   *
   * @return JsonResponse
   *
   * @internal param Request $request
   */
  public function setRequestFactureDataAction(Request $request, $id)
  {
    $productsData = json_decode($request->request->get('productsData'), true);
    $cardsData = json_decode($request->request->get('cardsData'), true);
    $requestDB = $this->getDoctrine()->getRepository('AppBundle:Request\Request')->find($id);
    $preFactures = $requestDB->getPreFactures();
    $factures = $requestDB->getFactures();

    foreach ($productsData as $productData) {
      $productId = $productData["data"]["id"];

      foreach ($preFactures as $preFacture) {
        $newCount = $productsData[$productId]["prefactures"][$preFacture->getId()."-".date_format($preFacture->getDate(), 'Y')];
        if ($newCount > 0) {
          $exists = false;
          foreach ($preFacture->getPreFactureProducts() as $preFactureProduct) {
            if ($preFactureProduct->getProduct()->getId() == $productId) {
              $preFactureProduct->setCount($preFactureProduct->getCount() + $newCount);
              $exists = true;
            }
          }
          if (!$exists) {
            $preFactureProduct = new PreFactureProduct();
            $preFactureProduct->setPreFacture($preFacture);
            $preFactureProduct->setProduct($this->getDoctrine()->getRepository('AppBundle:Product')->find($productId));
            $preFactureProduct->setCount($newCount);
            $preFactureProduct->setIsAriplaneForniture($productsData[$productId]["data"]["isAriplaneForniture"]);
            $preFactureProduct->setIsAriplaneMattress($productsData[$productId]["data"]["isAriplaneMattress"]);

            if (array_key_exists("offerId", $productsData[$productId]["data"])) {
              $preFactureProduct->setOffer($this->getDoctrine()->getRepository('AppBundle:Offer')->find($productsData[$productId]["data"]["offerId"]));
            }

            $this->getDoctrine()->getManager()->persist($preFactureProduct);
            $preFacture->addPreFactureProduct($preFactureProduct);
          }

          $requestProduct = $this->getDoctrine()->getRepository('AppBundle:Request\RequestProduct')->find($productsData[$productId]["data"]["requestProductId"]);
          $requestProduct->setCount($requestProduct->getCount() - $newCount);
        }
      }

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
            $factureProduct = new factureProduct();
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

          $requestProduct = $this->getDoctrine()->getRepository('AppBundle:Request\RequestProduct')->find($productsData[$productId]["data"]["requestProductId"]);
          $requestProduct->setCount($requestProduct->getCount() - $newCount);
        }
      }
    }

    foreach ($cardsData as $cardData) {
      $cardPrice = $cardData["data"]["price"];

      foreach ($preFactures as $preFacture) {
        $newCount = $cardsData[$cardPrice]["prefactures"][$preFacture->getId()."-".date_format($preFacture->getDate(), 'Y')];
        if ($newCount > 0) {
          $exists = false;
          foreach ($preFacture->getPreFactureCards() as $preFactureCard) {
            if ($preFactureCard->getPrice() == $cardPrice) {
              $preFactureCard->setCount($preFactureCard->getCount() + $newCount);
              $exists = true;
            }
          }
          if (!$exists) {
            $preFactureCard = new PreFactureCard();
            $preFactureCard->setPreFacture($preFacture);
            $preFactureCard->setPrice($cardPrice);
            $preFactureCard->setCount($newCount);

            $this->getDoctrine()->getManager()->persist($preFactureCard);
            $preFacture->addPreFactureCard($preFactureCard);
          }

          $requestCard = $this->getDoctrine()->getRepository('AppBundle:Request\RequestCard')->find($cardsData[$cardPrice]["data"]["requestCardId"]);
          $requestCard->setCount($requestCard->getCount() - $newCount);
        }
      }

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
            $factureCard = new factureCard();
            $factureCard->setFacture($facture);
            $factureCard->setPrice($cardPrice);
            $factureCard->setCount($newCount);

            $this->getDoctrine()->getManager()->persist($factureCard);
            $facture->addFactureCard($factureCard);
          }

          $requestCard = $this->getDoctrine()->getRepository('AppBundle:Request\RequestCard')->find($cardsData[$cardPrice]["data"]["requestCardId"]);
          $requestCard->setCount($requestCard->getCount() - $newCount);
        }
      }
    }

    $this->getDoctrine()->getManager()->flush();

    return new JsonResponse();
  }
}
