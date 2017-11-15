<?php

/*
 * Zend 2 API
 */

namespace AdminUser\Model\Sql\Update\AdminUser;

use Zend\Db\Sql\Update;
use AdminUser\Model\DataContainer\AdminUser\ForUpdate as AdminUser;
use Authentication\Model\Service\PasswordManager;

/**
 * 
 *  AdminUser select query
 *
 * @author Dmitry Lesov
 */
class Login extends Update {

    protected $adminUser;
    protected $passwordManager;
    private $updatableFields = [
                'login_status',
                'username',
                'password',
                'user_role',
                'first_name',
                'last_name',
                'email',
    ];

    public function __construct( AdminUser $adminUser, PasswordManager $passwordManager ) {

        parent::__construct();

        $this->adminUser            = $adminUser;
        $this->passwordManager = $passwordManager;

        $this->setTable();
        $this->setValues();
        $this->setWhere();
    }

    protected function setTable() {
        $this->table('login');
    }

    protected function setValues() {
        $this->set($this->getAdminUserValues());
    }

    protected function setWhere() {
        $this->where(['id' => $this->adminUser->getLoginId()]);
    }


    protected function getAdminUserValues() {
        $arr     = $this->adminUser->getArrayCopy();
        $return  = [];
        foreach ($this->updatableFields as $key) {
            if ( !empty($arr[$key]) ) {
                if ( 'password' === $key ) {
                    $return[$key] = $this->passwordManager->create_hash($arr[$key]);
                } else {
                    $return[$key] = $arr[$key];
                }
            }
        }
        return $return;
    }

}
