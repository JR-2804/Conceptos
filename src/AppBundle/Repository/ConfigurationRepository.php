<?php

namespace AppBundle\Repository;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping;

/**
 * ConfigurationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ConfigurationRepository extends \Doctrine\ORM\EntityRepository
{
    private $repository;
    public function __construct($em, Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->repository = $this->getEntityManager()->getRepository('AppBundle:Configuration');
    }

    public function getConfig(){
        $config = $this->repository->find(1);

        return $config;
    }

}