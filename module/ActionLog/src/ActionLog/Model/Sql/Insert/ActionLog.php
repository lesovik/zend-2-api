<?php

/*
 * Zend 2 API
 */

namespace ActionLog\Model\Sql\Insert;

use Zend\Db\Sql\Insert;
use ActionLog\Model\DataContainer\ActionLog as DataContainer;

/**
 * 
 *  insert ActionLog data into login table
 *
 * @author Dmitry Lesov
 */
class ActionLog extends Insert {

    protected $actionLog;

    public function __construct( DataContainer $actionLog) {

        parent::__construct();

        $this->actionLog            = $actionLog;
        $this->setInto();
        $this->setValues();
    }

    protected function setInto() {
        $this->into('action_log');
    }

    protected function setValues() {
        $this->values([
            'token_id'  => $this->actionLog->getTokenId(),
            'route'     => $this->actionLog->getRoute(),
            'method' => $this->actionLog->getMethod(),
            'data' => $this->actionLog->getData(),
            'response_code' => $this->actionLog->getResponseCode(),
        ]);
    }
}
