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
        'state' => $preFactureProduct->getState(),
      ];
      $preFactureProducts[] = $newPreFactureProduct;
    }
    $dto->setPreFactureProducts(json_encode($preFactureProducts));

    $form = $this->createForm(PreFactureType::class, $dto);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $preFactureDB = $this->getDoctrine()->getRepository('AppBundle:Request\PreFacture')->find($id);
      $preFactureProducts = json_decode($form->get('preFactureProducts')->getData(), true);
      if (!is_array($preFactureProducts)) {
        $preFactureProducts = [];
      }

      foreach ($preFactureDB->getPreFactureProducts() as $preFactureProductDb) {
        foreach ($preFactureProducts as $preFactureProduct) {
          if ($preFactureProduct["id"] == $preFactureProductDb->getId()) {
            $preFactureProductDb->setState($preFactureProduct["state"]);
            $this->getDoctrine()->getManager()->persist($preFactureProductDb);
          }
        }
      }
      $preFactureDB->getPreFactureProducts()->clear();

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
