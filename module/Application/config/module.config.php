<?php

namespace Application;

use Doctrine\ORM;
use Zend\Authentication\AuthenticationService;
use Zend\I18n\View\Helper\Translate;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Gedmo;

return [
    'router'             => [
        'routes' => [
            'home'           => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application'    => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'page'           => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/page/:code',
                    'constraints' => [
                        'code' => '([a-zA-Z0-9\-_\.]+)',
                    ],
                    'defaults'    => [
                        'controller' => Controller\PageController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'robots'         => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/robots.txt',
                    'defaults' => [
                        'controller' => Controller\RobotsController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'sitemap'        => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/sitemap.xml',
                    'defaults' => [
                        'controller' => Controller\SiteMapController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'change-version' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/change-version',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'change-version',
                    ],
                ],
            ],
            'catalog'        => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/catalog',
                    'defaults' => [
                        'controller' => Controller\CatalogController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'category'       => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/category/[:code]',
                    'constraints' => [
                        'code' => '([a-zA-Z0-9\-_\.]+)',
                    ],
                    'defaults'    => [
                        'controller' => Controller\CatalogController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'cart'           => [
                'type'          => Literal::class,
                'options'       => [
                    'route'    => '/cart',
                    'defaults' => [
                        'controller' => Controller\CartController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'add'    => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/add',
                            'defaults' => [
                                'controller' => Controller\CartController::class,
                                'action'     => 'add',
                            ],
                        ],
                    ],
                    'update' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/update',
                            'defaults' => [
                                'controller' => Controller\CartController::class,
                                'action'     => 'update',
                            ],
                        ],
                    ],
                ],
            ],
            'order'          => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/order',
                    'defaults' => [
                        'controller' => Controller\OrderController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'images'         => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/images[/:entity-directory][/:file-name]',
                    'constraints' => [
                        'entity-directory' => '[0-9]+',
                        'file-name'        => '[a-f0-9_]+[.][jp?eg|png|gif|ico]{3,4}',
                    ],
                    'defaults'    => [
                        'controller' => Controller\FileController::class,
                        'action'     => 'images',
                    ],
                ],
            ],
        ],
    ],
    'service_manager'    => [
        'aliases'    => [
            'translator' => 'MvcTranslator',
        ],
        'factories'  => [
            AuthenticationService::class      => Factory\Authentication\AuthenticationService::class,
            'Application\Permissions\Acl\Acl' => Factory\Permissions\Acl\Acl::class,
        ],
        'invokables' => [
            'doctrine_extensions.gedmo.uploadable' => Gedmo\Uploadable\UploadableListener::class,
        ],
    ],
    'controller_plugins' => [
        'aliases' => [
            'pageDriverAbout'   => Controller\Plugin\PageDriverAbout::class,
            'pageDriverContact' => Controller\Plugin\PageDriverContact::class,
        ],
    ],
    'view_manager'       => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map'             => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack'      => [
            __DIR__ . '/../view',
        ],
    ],
    'view_helpers'       => [
        'invokables' => [
            'translate' => Translate::class,
        ],
        'aliases'    => [
            'createPreviewSrc' => View\Helper\CreatePreviewSrc::class,
            'googleTagManager' => View\Helper\GoogleTagManager::class,
            'googleAnalytics'  => View\Helper\GoogleAnalytics::class,
            'seoData'          => View\Helper\SeoData::class,
            'header'           => View\Helper\Header::class,
            'footer'           => View\Helper\Footer::class,
            'currentUrl'       => View\Helper\CurrentUrl::class,
            'categoties'       => View\Helper\Categoties::class,
            'cart'             => View\Helper\Cart::class,
            'cartProduct'      => View\Helper\CartProduct::class,
        ],
    ],
    'doctrine'           => [
        'driver'         => [
            'application_entity' => [
                'class' => ORM\Mapping\Driver\AnnotationDriver::class,
                'paths' => __DIR__ . '/../src/Entity',
            ],
            'orm_default'        => [
                'drivers' => [
                    'Application\Entity' => 'application_entity',
                ],
            ],
        ],
        'authentication' => [
            'orm_default' => [
                'object_manager'      => ORM\EntityManager::class,
                'identity_class'      => Entity\User::class,
                'identity_property'   => 'email',
                'credential_property' => 'password',
                'credential_callable' => 'Application\Service\User::verifyCredential'
            ],
        ],
    ],
    'doctrine_factories' => [
        'authenticationadapter' => Factory\Authentication\Adapter\ObjectRepository::class,
    ],
    'session_containers' => [
        'CartContainer',
    ],
    'acl'                => [
        [
            'controller' => [
                Controller\IndexController::class,
                Controller\RobotsController::class,
                Controller\SiteMapController::class,
                Controller\PageController::class,
                Controller\CatalogController::class,
                Controller\CartController::class,
                Controller\OrderController::class,
            ],
            'roles'      => ['guest'],
        ],
    ],
    'google_tag_manager' => [
        'code' => '',
    ],
    'google_analytics'   => [
        'code' => '',
    ],
    'email'              => [
        'sendTopLimit' => 25,
        'maintenance'  => false,
        'support'      => [
            'email' => 'somesupportmail@gmail.com',
            'name'  => 'Some support mail',
        ],
        'noreply'      => [
            'email' => 'somenoreplymail@gmail.com',
            'name'  => 'Some noreply mail',
        ],
        'admins'       => [
            'gorbenko.aleksey@gmail.com' => 'Gorbenko Aleksey',
        ],
    ],
];
