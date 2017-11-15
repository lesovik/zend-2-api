<?php

/*
 * Zend 2 API
 */

namespace ActionLog\Model\DataContainer\ActionLog;

use ActionLog\Model\DataContainer\ActionLog;

/**
 * 
 *  ActionLog ActionLog SelectCriteria
 *
 * @author Dmitry Lesov
 */
class ForSelect extends ActionLog {

    protected static $keyMap = [

        'tokenId'      => [
            'name'       => 'token_id',
            'required'   => false,
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
        'route'        => [
            'name'       => 'route',
            'required'   => false,
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
        'method'       => [
            'name'       => 'method',
            'required'   => false,
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
        'responseCode' => [
            'name'       => 'response_code',
            'required'   => false,
            'validators' =>
            [
                [
                    'name'    => 'Zend\Validator\Digits',
                    'options' => [
                        'message' => 'Response Code must be an integer',
                    ],
                ],
            ],
        ],
        'skip'  => [
            'name'       => 'skip',
            'required'   => false,
            'validators' => [
                [
                    'name'    => 'Zend\Validator\Digits',
                    'options' => [
                        'message' => 'Skip must be an integer',
                    ],
                ],
            ],
        ],
        'limit' => [
            'name'       => 'limit',
            'required'   => false,
            'validators' =>
            [

                [
                    'name'    => 'Zend\Validator\Digits',
                    'options' => [
                        'message' => 'Limit must be an integer',
                    ],
                ],
            ],
        ],
    ];

}
