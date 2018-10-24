<?php

namespace Application\Factory\Authentication;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Authentication\AuthenticationService as ZendAuthenticationService;

class AuthenticationService implements FactoryInterface
{
    /**
     * Create authentication service
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     *
     * @return ZendAuthenticationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authenticationService = $container->get('doctrine.authenticationservice.orm_default');

        return $authenticationService;
    }
}
