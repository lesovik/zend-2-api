<?php

/*
 * Zend 2 API
 */

namespace AdminUser\Model\Sql\Select\AdminUser;

use AdminUser\Model\Sql\Select\AdminUser as Select;
use AdminUser\Model\DataContainer\AdminUser as Criteria;
use AdminUser\Model\DataContainer\AdminUser\ForSelect;

/**
 * 
 *  AdminUser select query filter
 *
 * @author Dmitry Lesov
 */
class Filter extends Select {

    /**
     * injects criteria container and sets up where statements
     * 
     * @dataProvider dataCriteria
     * @param Criteria $criteria
     */
    public function __construct( Criteria $criteria ) {
        parent::__construct();
        $arr = $criteria->getArrayCopy();

        if ( $loginId = $criteria->getLoginId() ) {
            $this->where(['login.id' => $loginId]);
        }
        if ( $loginStatus = $criteria->getLoginStatus() ) {
            $this->where(['login.login_status' => $loginStatus]);
        }

        if ( $lastName = $criteria->getLastName() ) {
            $this->where->expression(
                'upper(login.last_name) like ?', "%" . strtoupper($lastName) . "%");
        }
        if ( $firstName = $criteria->getFirstName() ) {
            $this->where->expression(
                'upper(login.first_name) like ?', "%" . strtoupper($firstName) . "%");
        }
        if ( $email = $criteria->getEmail() ) {
            $this->where->expression(
                'upper(login.email) like ?', "%" . strtoupper($email) . "%");
        }
        if ( $AdminUserRole = $criteria->getUserRole() ) {
            $this->where(['login.user_role' => $AdminUserRole]);
        }
        if ( $criteria instanceof ForSelect ) {
            if ( $sort = $criteria->getSort() ) {
                $this->order([$sort => $criteria->getOrder()]);
            }
        }
    }

}
