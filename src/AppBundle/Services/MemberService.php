<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;

class MemberService
{
    private $em;
    private $repo;

    /**
     * MemberService constructor.
     *
     * @param $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository('AppBundle:Member');
    }

    public function findByUser($userId)
    {
        return $this->repo->findOneBy([
            'user' => $userId,
        ]);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function findFavoriteProducts($userId)
    {
        return $this->em->getRepository('AppBundle:Product')->createQueryBuilder('p')
            ->join('p.userFavorites', 'mf')
            ->join('mf.user', 'm')
            ->where('m.id = :user')
            ->setParameter('user', $userId)
            ->getQuery()->getResult();
    }

    public function findFavoriteProductsEntity($userId)
    {
        return $this->em->getRepository('AppBundle:FavoriteProduct')->createQueryBuilder('fp')
            ->join('fp.user', 'u')
            ->where('u.id = :user')
            ->setParameter('user', $userId)
            ->getQuery()->getResult();
    }

    public function findFavoriteProduct($productId, $userId)
    {
        return $this->em->getRepository('AppBundle:FavoriteProduct')->findOneBy([
            'product' => $productId,
            'user' => $userId,
        ]);
    }
}
