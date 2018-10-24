<?php

namespace App\Initializers;

use Zend\Form\FieldsetInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Initializer\InitializerInterface;
use Zend\Hydrator\AbstractHydrator;

class Form implements InitializerInterface
{
    /**
     * Additional form initialization
     *
     * @param ContainerInterface $container
     * @param object $instance
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        if (!$instance instanceof FieldsetInterface) {
            return;
        }

        /** @var $instance FieldsetInterface */
        $hydrator = $container->get(AbstractHydrator::class);
        $instance->setHydrator($hydrator);
    }
}
