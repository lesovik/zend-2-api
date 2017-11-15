<?php

/*
 * Zend 2 API
 */

namespace ActionLog\Model\Service\ActionLog;

use ActionLog\Model\DataContainer\ActionLog;
use ActionLog\Model\Service\ActionLog as Service;
use ActionLog\Model\Sql\Select\ActionLog\Filter as Select;
use ActionLog\Model\DataContainer\ActionLog\ById as Criteria;

/**
 * 
 *  ActionLog getter by id
 *
 * @author Dmitry Lesov
 */
class Get extends Service {
    

    /**
     * 
     * execute()
     * 
     * attempts to build select query
     *
     * @param array $parameters
     * @return boolean 
     */
    public function execute( array $parameters ) {
        $criteria = new Criteria($parameters);
        $this->validator->setUp($criteria, $this->executer->getAdapter());
        if ( !$this->isValid($criteria->getArrayCopy()) ) {
            return false;
        }
        $resultSet  = $this->executer->execute(new Select($criteria), new ActionLog());
        $this->data = $resultSet->current()->getArrayCopy();
        return true;
    }
}
