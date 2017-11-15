<?php

/*
 * Zend 2 API
 */

namespace AdminUser\Model\Sql\Select;

use Zend\Db\Sql\Select;

/**
 * 
 *  AdminUser select query
 *
 * @author Dmitry Lesov
 */
class AdminUser extends Select {

    public function __construct() {
        parent::__construct();
        $this->setFrom();
        $this->setColumns();
    }

    protected function setFrom() {
        $this->from(['login' => 'login']);
    }

    protected function setColumns() {
        $this->columns([
            'id',
            'last_modified',
            'login_status',
            'user_role',
            'created',
            'username',
            'first_name',
            'last_name',
            'email',
        ]);
    }

    
   

}
