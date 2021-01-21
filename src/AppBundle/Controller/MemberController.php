<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityNotFoundException;

class MemberController extends Controller
{
    /**
     * @Route(name="favorite_products_user", path="/user/product/favorites")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function favoriteProductsAction(Request $request)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->find($request->query->get('id'));
        $products = $this->get('member_service')->findFavoriteProductsEntity($user->getId());

        $clientProducts = [];
        foreach($products as $product) {
          if ($this->IsEntityDefined($product)) {
            $clientProducts[] = $product;
          }
        }

        return $this->render(':admin/user:favorite-products.html.twig', [
            'products' => $clientProducts,
            'user' => $user,
            'menuIndex' => $request->query->get('menuIndex'),
            'submenuIndex' => $request->query->get('submenuIndex'),
        ]);
    }

    /**
     * @Route(name="shop_cart_user", path="/user/shop-cart")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userShopCartAction(Request $request)
    {
      $userId = $request->query->get("id");
      $user = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->find($userId);
      $shopCartProducts = $this->getDoctrine()->getManager()->getRepository('AppBundle:ShopCartProduct')->findBy([
        'user' => $userId,
      ]);

      $products = [];
      foreach ($shopCartProducts as $shopCartProducts) {
        $id = $shopCartProducts->getProductId();
        if ($id == 'target15' || $id == 'target25' || $id == 'target5' || $id == 'target100') {
          $name = 'Tarjeta de 15 CUC';
          switch ($id) {
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
          $products[] = [
            "type" => "card",
            "code" => $name,
            "count" => $shopCartProducts->getCount(),
            "price" => $price,
          ];
        } else {
          $product = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->find($id);
          if($product) {
            $price = $product->getPrice();
            $offerPrice = $this->get('product_service')->findProductOfferPrice($product);
            if ($offerPrice != -1) {
              $price = $offerPrice;
            }

            $products[] = [
              "code" => $product->getCode(),
              "image" => $product->getMainImage(),
              "count" => $shopCartProducts->getCount(),
              "price" => $price,
            ];
          }
        }
      }

      $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
        'name' => 'Membresia',
      ]);

      return $this->render(':admin/user:shop-cart.html.twig', [
        'user' => $user,
        'products' => $products,
        'membership' => $membership,
        'menuIndex' => $request->query->get('menuIndex'),
        'submenuIndex' => $request->query->get('submenuIndex'),
      ]);
    }

    /**
     * @Route(name="is_valid_membership_number", path="/validate-membership-number/{membershipNumber}", methods={"POST", "GET"})
     *
     * @param Request $request
     * @param mixed   $membershipNumber
     *
     * @return JsonResponse
     */
    public function isValidMembershipNumberAction(Request $request, $membershipNumber)
    {
        $member = $this->getDoctrine()->getManager()->getRepository('AppBundle:Member')->createQueryBuilder('m')
            ->where('m.number = :membershipNumber')
            ->setParameter('membershipNumber', $membershipNumber)
            ->getQuery()->getResult();

        $isValid = false;
        if ($member) {
            $isValid = true;
        }

        return new JsonResponse($isValid);
    }

    public function IsEntityDefined($favoriteProduct) {
      try {
        if ($favoriteProduct->getProduct() != null && $favoriteProduct->getProduct()->getName() != null) {
          return true;
        } else {
          return false;
        }
      } catch (EntityNotFoundException $e) {
        return false;
      }
    }
}
