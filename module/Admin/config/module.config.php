<?php

namespace Admin;

return array(
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Dashboard',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:action]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                        ),
                    ),
                ),
            ),
            'posts' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin/posts[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Posts',
                        'action' => 'index'
                    )
                ),
            ),
            'users' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin/users[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Users',
                        'action' => 'index'
                    )
                ),
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Admin\Controller\Posts' => 'Admin\Factory\Controller\PostsControllerFactory',
            'Admin\Controller\Users' => 'Admin\Factory\Controller\UsersControllerFactory',
            'Admin\Controller\Dashboard' => 'Admin\Factory\Controller\DashboardControllerFactory',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);
