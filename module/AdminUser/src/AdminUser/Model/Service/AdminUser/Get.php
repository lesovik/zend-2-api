<?php

/*
 * Zend 2 API
 */

namespace AdminUser\Model\Service\AdminUser;

use AdminUser\Model\DataContainer\AdminUser;
use AdminUser\Model\Service\AdminUser as Service;
use AdminUser\Model\Sql\Select\AdminUser\Filter as Select;
use AdminUser\Model\DataContainer\AdminUser\ById as Criteria;

/**
 *
 * AdminUser getter by id
 *
 * @author Dmitry Lesov
 */
class Get extends Service
{


    /**
     * @param array $parameters
     * @return boolean
     */
    public function execute(array $parameters)
    {
        $isExecuted = false;

        $criteria = new Criteria($parameters);
        $this->validator->setUp($criteria, $this->executer->getAdapter());

        if ($this->isValid($criteria->getArrayCopy())) {

            $resultSet = $this->executer->execute(new Select($criteria), new AdminUser());

            $this->data = $resultSet->current()->getArrayCopy();

            $isExecuted = true;
        }

        return $isExecuted;
    }
}