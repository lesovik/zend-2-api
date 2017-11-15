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
class ById extends AdminUser
{

    protected static $keyMap = [
        'loginId' => [
            'name' => 'id',
            'required' => true,
            'validators' =>
                [
                    [
                        'name' => 'Zend\Validator\Digits',
                        'break_chain_on_failure' => true,
                        'options' => [
                            'message' => 'AdminUser ID must be an integer',
                        ],
                    ],
                    [
                        'name' => 'Zend\Validator\Db\RecordExists',
                        'options' => [
                            'message' => 'AdminUser not found',
                            'table' => 'login',
                            'field' => 'id'
                        ],
                    ],
                ],
        ],
    ];
}
