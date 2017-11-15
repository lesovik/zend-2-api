<?php

/*
 * Zend 2 API
 */

namespace AdminUser\Model\DataContainer\AdminUser;

use AdminUser\Model\DataContainer\AdminUser;

/**
 * 
 *  ForInsert keymap for AdminUser Insertion 
 * not annotated fields are for storage outside the validation
 *
 * @author Dmitry Lesov
 */
class ForInsert extends AdminUser {

    protected static $keyMap = [
        'loginId'     => 'id',
        'adminUserRole'    => [
            'name'       => 'user_role',
            'required'   => true,
            'validators' =>
            [
                [
                    'name'    => 'Zend\Validator\InArray',
                    'options' => [
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
        'email'       => [
            'name'       => 'email',
            'required'   => true,
            'validators' =>
            [
                [
                    'name'    => 'EmailAddress',
                    'options' => [
                        'domain'   => 'true',
                        'hostname' => 'true',
                        'mx'       => 'true',
                        'deep'     => 'true',
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
        'firstName'   => [
            'name'       => 'first_name',
            'required'   => true,
            'validators' =>
            [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => '2',
                    ],
                ],
            ],
        ],
        'lastName'    => [
            'name'       => 'last_name',
            'required'   => true,
            'validators' =>
            [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => '2',
                    ],
                ],
            ],
        ],
      
    ];

}
