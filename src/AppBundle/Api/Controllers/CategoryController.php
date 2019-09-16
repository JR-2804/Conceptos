<?php

namespace AppBundle\Api\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    /**
     * @Route(name="api_get_categories", path="/categories")
     *
     * @internal param Request $request
     */
    public function getCategoriestAction()
    {
        $categories = $this->get('api_category_service')->getAll();

        return new JsonResponse($categories);
    }
}
