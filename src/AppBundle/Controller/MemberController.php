<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

        return $this->render(':admin/user:favorite-products.html.twig', [
            'products' => $products,
            'user' => $user,
            'menuIndex' => $request->query->get('menuIndex'),
            'submenuIndex' => $request->query->get('submenuIndex'),
        ]);
    }

    /**
     * @Route(name="is_valid_membership_number", path="/validate-membership-number/{membershipNumber}", methods={"POST"})
     *
     * @param Request $request
     * @param mixed   $membershipNumber
     *
     * @return JsonResponse
     */
    public function calculatePriceAction(Request $request, $membershipNumber)
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
}
