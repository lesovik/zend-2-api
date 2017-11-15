<?php

/*
 * Zend 2 API
 */

namespace AdminUser\Model\DataContainer\AdminUser;

use AdminUser\Model\DataContainer\AdminUser;

/**
 * 
 *  AdminUser SelectCriteria
 *
 * @author Dmitry Lesov
 */
class ForSelect extends AdminUser {

    protected static $keyMap = [

        'adminUserRole'    => [
            'name'       => 'user_role',
            'required'   => false,
            'validators' =>
            [
                [
                    'name'    => 'Zend\Validator\InArray',
                    'options' => [
                        'message'  => 'Not a valid entry',
                        'haystack' => [
                            'ADMIN',
                            'MANAGER',
                            'ROOT',
                            'INTERN'
                        ]
                    ],
                ],
            ],
        ],
        'loginStatus' => [
            'name'       => 'login_status',
            'required'   => false,
            'validators' =>
            [
                [
                    'name'    => 'Zend\Validator\InArray',
                    'options' => [
                        'message'  => 'Not a valid entry',
                        'haystack' => [
                            'ACTIVE',
                            'SUSPENDED'
                        ]
                    ],
                ],
            ],
        ],
        'firstName'   => [
            'name'       => 'first_name',
            'required'   => false,
            'validators' =>
            [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => '2',
                    ],
                ],
                [
                    'name'    => 'Alnum',
                    'options' => [
                        'message'         => 'First Name must be Alphanumeric',
                        'allowWhiteSpace' => true,
                    ],
                ],
            ],
        ],
        'lastName'    => [
            'name'       => 'last_name',
            'required'   => false,
            'validators' =>
            [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => '2',
                    ],
                ],
                [
                    'name'    => 'Alnum',
                    'options' => [
                        'message'         => 'Last Name must be Alphanumeric',
                        'allowWhiteSpace' => true,
                    ],
                ],
            ],
        ],
        'email'       => [
            'name'       => 'email',
            'required'   => false,
            'validators' =>
            [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => '5',
                    ],
                ],
            ],
        ],
        
        'sort'        => [
            'name'       => 'sort',
            'required'   => false,
            'validators' =>
            [
                [
                    'name'    => 'Zend\Validator\InArray',
                    'options' => [
                        'message'  => 'Not a valid entry ',
                        'haystack' => [
                            'id',
                            'first_name',
                            'last_name'
                        ]
                    ],
                ]
            ],
        ],
        'order'       => [
            'name'       => 'order',
            'required'   => false,
            'validators' =>
            [
                [
                    'name'    => 'Zend\Validator\InArray',
                    'options' => [
                        'message'  => 'Not a valid entry ',
                        'haystack' => [
                            'ASC',
                            'DESC'
                        ]
                    ],
                ]
            ],
        ],
        'skip'        => [
            'name'       => 'skip',
            'required'   => false,
            'validators' => [
                [
                    'name'    => 'Zend\Validator\Digits',
                    'options' => [
                        'message' => 'AdminUser ID must be an integer',
                    ],
                ],
            ],
        ],
        'limit'       => [
            'name'       => 'limit',
            'required'   => false,
            'validators' =>
            [

                [
                    'name'    => 'Zend\Validator\Digits',
                    'options' => [
                        'message' => 'AdminUser ID must be an integer',
                    ],
                ],
            ],
        ],
    ];

}
