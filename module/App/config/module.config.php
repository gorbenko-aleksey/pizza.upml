<?php

namespace App;

use Zend\ServiceManager\AbstractFactory;
use Zend\Hydrator\AbstractHydrator;

return [
    'service_manager' => [
        'abstract_factories' => [
            AbstractFactory\ReflectionBasedAbstractFactory::class,
        ],
        'factories' => [
            AbstractHydrator::class => Factory\Doctrine\Hydrator::class,
        ],
    ],
    'controllers' => [
        'abstract_factories' => [
            AbstractFactory\ReflectionBasedAbstractFactory::class,
        ],
    ],
    'controller_plugins' => [
        'aliases' => [
            'sentry' => Controller\Plugin\Sentry::class,
            'translator' => Controller\Plugin\Translator::class,
        ],
        'abstract_factories' => [
            AbstractFactory\ReflectionBasedAbstractFactory::class,
        ],
    ],
    'view_manager' => [
        'base path' => '/',
        'base_path_console' => '/',
    ],
    'view_helpers' => [
        'abstract_factories' => [
            AbstractFactory\ReflectionBasedAbstractFactory::class,
        ],
    ],
    'sentry' => [
        'php' => [
            'enable' => false,
            'release' => 'master',
            'environment' => 'production',
            'dsn' => 'https://username:password@sentry.io/id',
        ],
    ],
    'form_elements'      => [
        'abstract_factories' => [
            AbstractFactory\ReflectionBasedAbstractFactory::class,
        ],
        'initializers' => [
            Initializers\Form::class
        ],
    ],
];
