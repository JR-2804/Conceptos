<?php

namespace AppBundle\Api\Services;

use Doctrine\ORM\EntityManager;

class CategoryService
{
    private $categoryRepository;

    public function __construct(EntityManager $em)
    {
        $this->categoryRepository = $em->getRepository('AppBundle:Category');
    }

    public function getAll()
    {
        $categories = $this->categoryRepository->findAll();

        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
            ];
        }

        return $result;
    }
}
