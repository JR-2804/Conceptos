<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;

class ColorService
{
  private $colorRepository;

  public function __construct(EntityManager $em)
  {
    $this->colorRepository = $em->getRepository('AppBundle:Color');
  }

  public function getAll()
  {
    return $this->colorRepository
      ->createQueryBuilder('color')
      ->orderBy('color.name', 'ASC')
      ->getQuery()
      ->getResult();
  }
}
