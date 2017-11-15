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
class ById extends ActionLog {

    protected static $keyMap = [
        'actionLogId'   => [
            'name'       => 'id',
            'required'   => true,
            'validators' =>
            [
                [
                    'name'                   => 'Zend\Validator\Digits',
                    'break_chain_on_failure' => true,
                    'options'                => [
                        'message' => 'ID must be an integer',
                    ],
                ],
                [
                    'name'    => 'Zend\Validator\Db\RecordExists',
                    'options' => [
                        'table'   => 'action_log',
                        'field'   => 'id'
                    ],
                ],
            ],
        ],    
    ];

}
