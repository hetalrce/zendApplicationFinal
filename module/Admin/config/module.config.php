<?php

namespace Admin;

Use Admin\Controller\Dashboard;
Use Admin\Controller\Posts;
use Admin\Controller\Users;
use Admin\Factory\Controller\PostsControllerFactory;
use Admin\Factory\Controller\UsersControllerFactory;
use Admin\Factory\Controller\DashboardControllerFactory;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/admin',
                    'defaults' => [
                        'controller' => Dashboard::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/[:action]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                        ],
                    ],
                ],
            ],
            'posts' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/admin/posts[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Posts::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'users' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/admin/users[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Users::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            'Admin\Controller\Posts' => PostsControllerFactory::class,
            'Admin\Controller\Users' => UsersControllerFactory::class,
            'Admin\Controller\Dashboard' => DashboardControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    // Doctrine config
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'],
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                ],
            ],
        ],
    ],
];
