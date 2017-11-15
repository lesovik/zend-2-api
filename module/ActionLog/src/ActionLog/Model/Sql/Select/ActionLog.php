<?php

/*
 * Zend 2 API
 */

namespace ActionLog\Model\Sql\Select;

use Zend\Db\Sql\Select;

/**
 * 
 *  ActionLog select query
 *
 * @author Dmitry Lesov
 */
class ActionLog extends Select {

    public function __construct() {
        parent::__construct();
        $this->setFrom();
        $this->setColumns();
    }

    protected function setFrom() {
        $this->from(['action_log' => 'action_log']);
    }

    protected function setColumns() {
        $this->columns([
            'id',
            'token_id',
            'route',
            'method',
            'data',
            'response_code'
        ]);
    }

}
