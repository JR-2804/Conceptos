<?php

namespace AppBundle\Services;

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
        return $this->categoryRepository
          ->createQueryBuilder('category')
          ->orderBy('category.priority', 'DESC')
          ->getQuery()
          ->getResult();
    }
}
