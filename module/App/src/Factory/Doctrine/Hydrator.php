<?php

namespace App\Factory\Doctrine;

use Interop\Container\ContainerInterface;
use Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Hydrator\AbstractHydrator;

class Hydrator
{
    /**
     * @param ContainerInterface $container
     *
     * @return AbstractHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        $em = $container->get('doctrine.entitymanager.orm_default');
        $hydrator = new DoctrineObject($em);
        $hydrator->setNamingStrategy(new UnderscoreNamingStrategy());

        return $hydrator;
    }
}