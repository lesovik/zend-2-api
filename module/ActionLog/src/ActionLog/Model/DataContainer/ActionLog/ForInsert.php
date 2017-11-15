<?php

/*
 * Zend 2 API
 */

namespace ActionLog\Model\DataContainer\ActionLog;

use ActionLog\Model\DataContainer\ActionLog;

/**
 * 
 *  ForInsert keymap for ActionLog Insertion 
 * not annotated fields are for storage outside the validation
 *
 * @author Dmitry Lesov
 */
class ForInsert extends ActionLog {

    protected static $keyMap = [
        'tokenId'   => [
            'name'       => 'token_id',
            'required'   => true,
            'validators' =>
            [
                [
                    'name'                   => 'Zend\Validator\Digits',
                    'break_chain_on_failure' => true,
                    'options'                => [
                        'message' => 'Token ID must be an integer',
                    ],
                ],
                [
                    'name'    => 'Zend\Validator\Db\RecordExists',
                    'options' => [
                        'message' => 'AccessToken not found',
                        'table'   => 'access_token',
                        'field'   => 'id'
                    ],
                ],
            ],
        ],
        'route'       => [
            'name'       => 'route',
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
        'method'   => [
            'name'       => 'method',
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
        'data'    => 'data',
        'responseCode'   => [
            'name'       => 'response_code',
            'required'   => true,
            'validators' =>
            [
                [
                    'name'                   => 'Zend\Validator\Digits',
                    'options'                => [
                        'message' => 'Response Code must be an integer',
                    ],
                ],
            ],
        ],
      
    ];

}
