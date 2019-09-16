<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;

class MaterialService
{
    private $materialRepository;

    public function __construct(EntityManager $em)
    {
        $this->materialRepository = $em->getRepository('AppBundle:Material');
    }

    public function getAll()
    {
        return [
            'materials' => $this->materialRepository
                ->createQueryBuilder('material')
                ->orderBy('material.name', 'ASC')
                ->getQuery()
                ->getResult(),
        ];
    }
}
