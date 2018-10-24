<?php

namespace Application\Factory\Authentication\Adapter;

use Interop\Container\ContainerInterface;
use DoctrineModule\Service\Authentication\AdapterFactory as BaseAdapterFactory;
use Application\Authentication\Adapter\ObjectRepository as ObjectRepositoryAdapter;

class ObjectRepository extends BaseAdapterFactory
{
    /**
     * Create authentication adapter
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     *
     * @return ObjectRepositoryAdapter
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var $options \DoctrineModule\Options\Authentication */
        $options = $this->getOptions($container, 'authentication');

        if (is_string($objectManager = $options->getObjectManager())) {
            $options->setObjectManager($container->get($objectManager));
        }

        return new ObjectRepositoryAdapter($options, $container);
    }
}
