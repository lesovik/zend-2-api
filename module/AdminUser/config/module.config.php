<?php

return [
    'router' => [
        'routes' => [
            'admin-user' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/admin-users[/:id]',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'AdminUser\Controller\AdminUser',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        ],
        'aliases' => [],
    ],
    'controllers' => [
        'invokables' => [
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
