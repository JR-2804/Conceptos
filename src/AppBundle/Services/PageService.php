<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;

class PageService
{
    private $pageRepository;

    public function __construct(EntityManager $em)
    {
        $this->pageRepository = $em->getRepository('AppBundle:Page\Page');
    }

    public function getHome()
    {
        return ['home' => $this->pageRepository->findOneBy(['name' => 'Home'])];
    }
}
