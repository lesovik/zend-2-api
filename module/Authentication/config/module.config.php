<?php

return [
    'router' => [
        'routes' => [
            'login' => [
                'type' => 'method',
                'options' => [
                    'verb' => 'post,options',
                ],
                'child_routes' => [
                    'post' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/auth/login',
                            'defaults' => [
                                'controller' => 'Authentication\Controller\Login',
                            ]
                        ],
                    ],
                ]
            ],
            'me' => [
                'type' => 'method',
                'options' => [
                    'verb' => 'get,options',

                ],
                'child_routes' => [
                    'get' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/auth/me',
                            'defaults' => [
                                'controller' => 'AdminUser\Controller\AdminUser',
                                'action' => 'me'
                            ]
                        ],
                    ],
                ]
            ],
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        ],
        'aliases' => [
        ],
    ],
    'controllers' => [
        'invokables' => [
        ],
    ],
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(),
        ),
    ),
];
