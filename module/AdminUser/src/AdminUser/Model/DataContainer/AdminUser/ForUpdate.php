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
                        'message' => 'AdminUser ID must be an integer',
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
                    'name'    => 'Zend\I18n\Validator\Alnum',
                    'options' => [
                        'allowWhiteSpace' => true,
                    ],
                ],
            ],
        ],
    ];

}
