<?php

return [
    'loggableMethods' => [
        'GET',
        'POST',
        'PUT',
        'DELETE',
    ],
    'maskedFields' => [
        'password',
    ],
    'mask' => '**secure**',
    'successOnly'     => false,
    'router'          => [
        'routes' => [
            'action-log' => [
                'type'         => 'method',
                'options'      => [
                    'verb' => 'get,options',
                ],
                'child_routes' => [
                    'get' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/action-log[/:actionLogId]',
                            'defaults' => [
                                'controller' => 'ActionLog\Controller\ActionLog',
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
        'aliases'            => [],
    ],
    'controllers'     => [
        'invokables' => [
        ],
    ],
    'view_manager'    => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
