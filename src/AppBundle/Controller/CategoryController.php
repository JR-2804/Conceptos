<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{

    /**
     * @Route(name="prod_by_category", path="/admin/category/product")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productByCategoryAction(Request $request)
    {
        $repoCategory = $this->getDoctrine()->getRepository('AppBundle:Category');
        $parents = $repoCategory->createQueryBuilder('c')
            ->where('c.subCategories IS NOT EMPTY')->getQuery()->getResult();

        $result = array();
        foreach ($parents as $category) {
            $categoryArr = array(
                'parent' => array(
                    'name' => $category->getName(),
                    'count' => 0
                ),
                'children' => array()
            );
            $children = $repoCategory->createQueryBuilder('c')
                ->join('c.parents', 'p')
                ->where('p.id = :parent')
                ->setParameter('parent', $category->getId())->getQuery()->getResult();
            $countParent = 0;
            foreach ($children as $child) {
                $countChild = $this->coundProductsByCategory($child->getId());
                $categoryArr['children'][] = array(
                    'name' => $child->getName(),
                    'count' => $countChild
                );
                $countParent += $countChild;
            }
            $categoryArr['parent']['count'] = $countParent;
            $result[] = $categoryArr;
        }
        return $this->render('prod_category.html.twig', array(
            'categories' => $result
        ));
    }

    /**
     * @Route(name="assign_category", path="/admin/category/assign")
     * @internal param Request $request
     */
    public function assignCategoryTextAction()
    {
        $manager = $this->getDoctrine()->getEntityManager();
        $products = $manager->getRepository('AppBundle:Product')->findAll();
        foreach ($products as $product) {
            $product->setCategoryText($product->getCategory()->getName());
        }
        $manager->flush();
        return new JsonResponse('ok');
    }

    private function coundProductsByCategory($categoryId)
    {
        $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
        $qb->select('COUNT (p)')
            ->from('AppBundle:Product', 'p')
            ->join('p.categories', 'c')
            ->where('c.id = :category')
            ->setParameter('category', $categoryId);
        return $qb->getQuery()->getSingleScalarResult();
    }
}