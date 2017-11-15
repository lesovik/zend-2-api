<?php

/*
 * Zend 2 API
 */

namespace AdminUser\Model\Sql\Insert\AdminUser;

use Zend\Db\Sql\Insert;
use AdminUser\Model\DataContainer\AdminUser;
use Authentication\Model\Service\PasswordManager;

/**
 * 
 *  insert AdminUser data into login table
 *
 * @author Dmitry Lesov
 */
class Login extends Insert {

    protected $adminUser;
    protected $passwordManager;
    protected $rnd;

    public function __construct( AdminUser $adminUser, PasswordManager $passwordManager ) {

        parent::__construct();

        $this->adminUser            = $adminUser;
        $this->passwordManager = $passwordManager;

        $this->setInto();
        $this->setValues();
    }

    protected function setInto() {
        $this->into('login');
    }

    protected function setValues() {
        $this->values([
            'username'  => $this->generateAdminUsername(),
            'email'     => $this->adminUser->getEmail(),
            'user_role' => $this->adminUser->getUserRole(),
            'first_name' => $this->adminUser->getFirstName(),
            'last_name' => $this->adminUser->getLastName(),
            'password'  => $this->generatePassword(),
        ]);
    }

    protected function generateAdminUsername() {
        return substr($this->getRnd(), 18, 8);
    }

    protected function generatePassword() {
        return $this->passwordManager->create_hash(
                substr($this->getRnd(), 4, 8)
        );
    }

    protected function getRnd() {
        if ( !$this->rnd ) {
            $this->rnd = strtr(strtolower(md5(rand(10000000, 99999999))),
                '0123456789abcdef', 'bcghjklmnqrtvwxz');
        }
        return $this->rnd;
    }

}
