<?php

namespace AppAdmin;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'console'            => [
        'router' => [
            'routes' => [
                'email-send-top' => [
                    'options' => [
                        'route'    => 'email-send-top',
                        'defaults' => [
                            'controller' => Controller\ConsoleController::class,
                            'action'     => 'send-top',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'router'             => [
        'routes' => [
            'admin' => [
                'type'          => Literal::class,
                'options'       => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'signin'                        => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/signin',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action'     => 'signin',
                            ],
                        ],
                    ],
                    'signout'                       => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/signout',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action'     => 'signout',
                            ],
                        ],
                    ],
                    'forgot-password'               => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/forgot-password',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action'     => 'forgot-password',
                            ],
                        ],
                    ],
                    'forgot-password-confirm'       => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/forgot-password-confirm/:secret',
                            'defaults' => [
                                'controller'  => Controller\AuthController::class,
                                'action'      => 'forgot-password-confirm',
                                'constraints' => [
                                    'secret' => '[a-fA-F0-9]*',
                                ],
                            ],
                        ],
                    ],
                    'password-confirm-mail-sent'    => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/password-confirm-mail-sent',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action'     => 'passwordConfirmMailSent',
                            ],
                        ],
                    ],
                    'password-changed-successfully' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/password-changed-successfully',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action'     => 'passwordChangedSuccessfully',
                            ],
                        ],
                    ],
                    'user'                          => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'    => '/user',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'edit'          => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/edit/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\UserController::class,
                                        'action'     => 'edit',
                                    ],
                                ],
                            ],
                            'edit-password' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/edit-password/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\UserController::class,
                                        'action'     => 'editPassword',
                                    ],
                                ],
                            ],
                            'delete'        => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/delete/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\UserController::class,
                                        'action'     => 'delete',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'category'                      => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'    => '/category',
                            'defaults' => [
                                'controller' => Controller\CategoryController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'edit'   => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/edit/[:parent]/[:current]',
                                    'constraints' => [
                                        'id'      => '[0-9_-]+',
                                        'current' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\CategoryController::class,
                                        'action'     => 'edit',
                                    ],
                                ],
                            ],
                            'delete' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/delete/[:current]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\CategoryController::class,
                                        'action'     => 'delete',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'order'                          => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'    => '/order',
                            'defaults' => [
                                'controller' => Controller\OrderController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'approve'   => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/approve/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\OrderController::class,
                                        'action'     => 'approve',
                                    ],
                                ],
                            ],
                            'set-status' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/set-status/[:id]/[:status]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                        'status' => '[0-4]',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\OrderController::class,
                                        'action'     => 'set-status',
                                    ],
                                ],
                            ],
                            'get-receipt' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/get-receipt/[:id]/[:product_id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                        'product_id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\OrderController::class,
                                        'action'     => 'get-receipt',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'product'                       => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'    => '/product',
                            'defaults' => [
                                'controller' => Controller\ProductController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'edit'   => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/edit/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\ProductController::class,
                                        'action'     => 'edit',
                                    ],
                                ],
                            ],
                            'delete' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/delete/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\ProductController::class,
                                        'action'     => 'delete',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'ingredient'                    => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'    => '/ingredient',
                            'defaults' => [
                                'controller' => Controller\IngredientController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'edit'   => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/edit/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\IngredientController::class,
                                        'action'     => 'edit',
                                    ],
                                ],
                            ],
                            'delete' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/delete/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\IngredientController::class,
                                        'action'     => 'delete',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'income' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/income',
                            'defaults' => [
                                'controller' => Controller\IncomeController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'edit' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/edit/[:id]',
                                    'constraints' => [
                                        'id' => '[0]+',
                                    ],
                                    'defaults' => [
                                        'controller' => Controller\IncomeController::class,
                                        'action' => 'edit',
                                    ],
                                ],
                            ],
                            'delete'        => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/delete/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\IncomeController::class,
                                        'action'     => 'delete',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'recipe'                        => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'    => '/recipe',
                            'defaults' => [
                                'controller' => Controller\ReceiptController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'edit'   => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/edit/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\ReceiptController::class,
                                        'action'     => 'edit',
                                    ],
                                ],
                            ],
                            'delete' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/delete/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\ReceiptController::class,
                                        'action'     => 'delete',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'white-ip'                      => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'    => '/white-ip',
                            'defaults' => [
                                'controller' => Controller\WhiteIpController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'edit'   => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/edit/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\WhiteIpController::class,
                                        'action'     => 'edit',
                                    ],
                                ],
                            ],
                            'delete' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/delete/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\WhiteIpController::class,
                                        'action'     => 'delete',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'seo'                           => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'    => '/seo',
                            'defaults' => [
                                'controller' => Controller\SeoController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'edit'            => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/edit/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\SeoController::class,
                                        'action'     => 'edit',
                                    ],
                                ],
                            ],
                            'delete'          => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/delete/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\SeoController::class,
                                        'action'     => 'delete',
                                    ],
                                ],
                            ],
                            'change-position' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/change-position/[:id]/[:position]',
                                    'constraints' => [
                                        'id'       => '[0-9_-]+',
                                        'position' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\SeoController::class,
                                        'action'     => 'change-position',
                                    ],
                                ],
                            ],
                            'edit-robots'     => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'    => '/edit-robots',
                                    'defaults' => [
                                        'controller' => Controller\RobotsController::class,
                                        'action'     => 'edit',
                                    ],
                                ],
                            ],
                            'edit-site-map'   => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'    => '/edit-site-map',
                                    'defaults' => [
                                        'controller' => Controller\SiteMapController::class,
                                        'action'     => 'edit',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'page'                          => [
                        'type'          => Literal::class,
                        'options'       => [
                            'route'    => '/page',
                            'defaults' => [
                                'controller' => Controller\PageController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'edit'   => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/edit/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\PageController::class,
                                        'action'     => 'edit',
                                    ],
                                ],
                            ],
                            'delete' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'       => '/delete/[:id]',
                                    'constraints' => [
                                        'id' => '[0-9_-]+',
                                    ],
                                    'defaults'    => [
                                        'controller' => Controller\PageController::class,
                                        'action'     => 'delete',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager'    => [
        'factories'  => [
            'admin-navigation' => Factory\Navigation\Service\Admin::class,
        ],
    ],
    'controllers'        => [
        'factories' => [],
    ],
    'controller_plugins' => [
        'aliases' => [
            'comeBack' => Controller\Plugin\ComeBack::class,
        ],
    ],
    'view_manager'       => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies'          => [
            'ViewJsonStrategy',
        ],
    ],
    'view_helpers'       => [
        'aliases' => [
            'adminMenu'                          => View\Helper\Menu::class,
            'adminPagination'                    => View\Helper\Pagination::class,
            'adminRowsPerPage'                   => View\Helper\RowsPerPage::class,
            'adminRenderForm'                    => View\Helper\RenderForm::class,
            'adminFlashMessenger'                => View\Helper\FlashMessenger::class,
            'adminCopyright'                     => View\Helper\Copyright::class,
            'urlWithParams'                      => View\Helper\UrlWithParams::class,
            'adminRenderFilterForm'              => View\Helper\RenderFilterForm::class,
            'comeBackUrl'                        => View\Helper\ComeBackUrl::class,
            'sortingData'                        => View\Helper\SortingData::class,
            'adminRenderTabForm'                 => View\Helper\RenderTabsForm::class,
            'weightFormatter'                    => View\Helper\FormatWeight::class,
            'adminRenderTableIngredientFieldset' => View\Helper\RenderTableIngredientFieldset::class,
        ],
    ],
    'acl'                => [
        [
            'controller' => [
                Controller\AuthController::class,
            ],
            'roles'      => ['guest'],
        ],
        [
            'controller' => [
                Controller\IndexController::class,
                Controller\OrderController::class,
            ],
            'roles'      => ['operator', 'сook', 'driver', 'admin'],
        ],
        [
            'controller' => [
                Controller\UserController::class,
                Controller\CategoryController::class,
                Controller\ProductController::class,
                Controller\IngredientController::class,
                Controller\IncomeController::class,
                Controller\ReceiptController::class,
                Controller\WhiteIpController::class,
                Controller\SeoController::class,
                Controller\PageController::class,
                Controller\RobotsController::class,
                Controller\SiteMapController::class,
                Controller\FileController::class,
            ],
            'roles'      => ['admin'],
        ],
    ],
    'navigation'         => [
        'admin' => [
            [
                'label'    => 'Dashboard',
                'route'    => 'admin',
                'resource' => Controller\IndexController::class,
                'class'    => 'fa fa-th-large',
            ],
            [
                'label'    => 'Users',
                'route'    => 'admin/user',
                'resource' => Controller\UserController::class,
                'class'    => 'fa fa-users',
                'pages'    => [
                    [
                        'route'   => 'admin/user/edit',
                        'visible' => false,
                    ],
                ],
            ],
            [
                'label'    => 'Categories',
                'route'    => 'admin/category',
                'resource' => Controller\CategoryController::class,
                'class'    => 'fa fa-folder',
                'pages'    => [
                    [
                        'route'   => 'admin/category/edit',
                        'visible' => false,
                    ],
                ],
            ],
            [
                'label'    => 'Orders',
                'route'    => 'admin/order',
                'resource' => Controller\OrderController::class,
                'class'    => 'fa fa-vcard',
                'pages'    => [
                    [
                        'route'   => 'admin/order/edit',
                        'visible' => false,
                    ],
                ],
            ],
            [
                'label'    => 'Products',
                'route'    => 'admin/product',
                'resource' => Controller\ProductController::class,
                'class'    => 'fa fa-list',
                'pages'    => [
                    [
                        'route'   => 'admin/product/edit',
                        'visible' => false,
                    ],
                ],
            ],
            [
                'label'    => _('Ingredients'),
                'route'    => 'admin/ingredient',
                'resource' => Controller\IngredientController::class,
                'class'    => 'fa fa-building-o',
                'pages'    => [
                    [
                        'route'   => 'admin/ingredient/edit',
                        'visible' => false,
                    ],
                    [
                        'route'   => 'admin/ingredient/history',
                        'visible' => false,
                    ],
                ],
            ],
            [
                'label' => _('Ingredients income'),
                'route' => 'admin/income',
                'resource' => Controller\IncomeController::class,
                'class' => 'fa fa-indent',
                'pages' => [
                    [
                        'route' => 'admin/income/edit',
                        'visible' => false,
                    ],
                ],
            ],
            [
                'label'    => _('Recipes'),
                'route'    => 'admin/recipe',
                'resource' => Controller\ReceiptController::class,
                'class'    => 'fa fa-book',
                'pages'    => [
                    [
                        'route'   => 'admin/recipe/edit',
                        'visible' => false,
                    ],
                    [
                        'route'   => 'admin/recipe/history',
                        'visible' => false,
                    ],
                ],
            ],
            [
                'label'    => 'Pages',
                'route'    => 'admin/page',
                'resource' => Controller\PageController::class,
                'class'    => 'fa fa-file-code-o',
                'pages'    => [
                    [
                        'route'   => 'admin/page/edit',
                        'visible' => false,
                    ],
                ],
            ],
            [
                'label'    => 'Seo section',
                'resource' => Controller\SeoController::class,
                'route'    => 'admin/seo',
                'class'    => 'fa fa-area-chart',
                'pages'    => [
                    [
                        'label'    => 'Seo rules',
                        'route'    => 'admin/seo',
                        'resource' => Controller\SeoController::class,
                        'pages'    => [
                            [
                                'route'   => 'admin/seo/edit',
                                'visible' => false,
                            ],
                        ],
                    ],
                    [
                        'label'    => 'Edit robots.txt',
                        'route'    => 'admin/seo/edit-robots',
                        'resource' => Controller\RobotsController::class,
                    ],
                    [
                        'label'    => 'Edit sitemap.xml',
                        'route'    => 'admin/seo/edit-site-map',
                        'resource' => Controller\SiteMapController::class,
                    ],
                ],
            ],
            [
                'label'    => 'White ip addresses',
                'route'    => 'admin/white-ip',
                'resource' => Controller\WhiteIpController::class,
                'class'    => 'fa fa-lock',
                'pages'    => [
                    [
                        'route'   => 'admin/white-ip/edit',
                        'visible' => false,
                    ],
                ],
            ],
        ],
    ],
];
