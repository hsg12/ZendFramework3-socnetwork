<?php

namespace Admin;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'statuses' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'       => '/statuses[/:action[/:id]]',
                            'constraints' => [
                                'action' => '[a-z]*',
                                'id'     => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\StatusController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'users' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'       => '/users[/:action[/:id]]',
                            'constraints' => [
                                'action' => '[a-z]*',
                                'id'     => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class  => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'admin/index/index' => __DIR__ . '/../view/admin/index/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'navigation' => [
        'default' => [
            'admin' => [
                'label' => 'Admin area',
                'route' => 'admin',
                'pages' => [
                    'status' => [
                        'label' => 'Statuses',
                        'route' => 'admin/statuses',
                    ],
                    'user' => [
                        'label' => 'Users',
                        'route' => 'admin/users',
                        'pages' => [
                            'edit' => [
                                'label' => 'Edit',
                                'route' => 'admin/users',
                                'action' => 'edit',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
