<?php

/*
 * Zend 2 API
 */

namespace AdminUser\Model\DataContainer\AdminUser;

use AdminUser\Model\DataContainer\AdminUser;

/**
 * 
 *  AdminUser
 *
 * @author Dmitry Lesov
 */
class ForUpdate extends AdminUser {

    protected static $keyMap = [
        'loginId'        => [
            'name'       => 'id',
            'required'   => true,
            'validators' =>
            [

                [
                    'name'                   => 'Zend\Validator\Digits',
                    'break_chain_on_failure' => true,
                    'options'                => [
                        //'message' => 'AdminUser ID must be an integer',
                    ],
                ],
                [
                    'name'    => 'Zend\Validator\Db\RecordExists',
                    'options' => [
                        'table'   => 'login',
                        'field'   => 'id'
                    ],
                ],
            ],
        ],
        'firstName'      => [
            'name'        => 'first_name',
            'required'    => false,
            'allow_empty' => false,
            'validators'  =>
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
                        'allowWhiteSpace' => true,
                    ],
                ],
            ],
        ],
        'adminUsername'       => [
            'name'        => 'username',
            'required'    => false,
            'allow_empty' => false,
            'validators'  =>
            [
                [
                    'name'    => 'Alnum',
                    'options' => [
                        'allowWhiteSpace' => false,
                    ],
                ],
                [
                    'name'    => 'Zend\Validator\Db\NoRecordExists',
                    'options' => [
                        'table' => 'login',
                        'field' => 'username'
                    ],
                ],
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => '6',
                    ],
                ],
            ],
        ],
        'password'       => [
            'name'        => 'password',
            'required'    => false,
            'allow_empty' => false,
            'validators'  =>
            [
                [
                    'name'    => 'Zend\Validator\Regex',
                    'options' => [
                        'message' => 'Password must contain one of following: uppercase character, lowercase character, non-alphabetic character',
                        'pattern' => '/^(?=.*[^a-zA-Z])(?=.*[a-z])(?=.*[A-Z])\S{8,}/',
                    ],
                ],
            ],
        ],
        'lastName'       => [
            'name'        => 'last_name',
            'required'    => false,
            'allow_empty' => false,
            'validators'  =>
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
                        'allowWhiteSpace' => true,
                    ],
                ],
            ],
        ],
        'email'          => [
            'name'        => 'email',
            'required'    => false,
            'allow_empty' => false,
            'validators'  =>
            [
                [
                    'name'    => 'EmailAddress',
                    'options' => [
                        'domain'   => 'true',
                        'hostname' => 'true',
                        'mx'       => 'true',
                        'deep'     => 'true',
                        'message'  => 'Invalid email address',
                    ],
                ],
                [
                    'name'    => 'Zend\Validator\Db\NoRecordExists',
                    'options' => [
                        'table' => 'login',
                        'field' => 'email'
                    ],
                ],
            ],
        ],
        'adminUserRole'       => [
            'name'        => 'user_role',
            'required'    => false,
            'allow_empty' => false,
            'validators'  =>
            [
                [
                    'name'    => 'Zend\Validator\InArray',
                    'options' => [
                        'message'  => 'Not a valid entry',
                        'haystack' => [
                            'ROOT',
                            'ADMIN',
                            'MANAGER',
                            'INTERN'
                        ]
                    ],
                ],
            ],
        ],
        'loginStatus'    => [
            'name'     => 'login_status',
            'required' => false,
            'allow_empty' => false,
            'validators'  =>
            [
                [
                    'name'    => 'Zend\Validator\InArray',
                    'options' => [
                        'haystack' => [
                            'ACTIVE',
                            'SUSPENDED',
                            'DELETED',
                        ]
                    ],
                ],
            ],
        ],
      
    ];

}
