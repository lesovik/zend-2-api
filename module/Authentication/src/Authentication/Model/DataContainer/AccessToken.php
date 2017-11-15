<?php

/*
 * Zend 2 API
 * 
 */

namespace Authentication\Model\DataContainer;

use Core\DataContainer;

/**
 * 
 *  AccessToken
 *
 * @author Dmitry Lesov
 */
class AccessToken extends DataContainer {

    protected $id       = null;
    protected $loginId  = null;
    protected $token    = null;
    protected $ip       = null;
    protected $expires  = null;
    protected $userRole = null;
    protected $firstName     = null;
    protected $lastName      = null;
    protected static $keyMap = [
        'id'        => 'id',
        'loginId'   => 'login_id',
        'token'     => 'token',
        'ip'        => 'ip',
        'expires'   => 'expires',
        'userRole'  => 'user_role',
        'firstName' => 'first_name',
        'lastName'  => 'last_name'
    ];

    public function getId() {
        return $this->id;
    }

    public function getLoginId() {
        return $this->loginId;
    }

    public function getToken() {
        return $this->token;
    }

    public function getIp() {
        return $this->ip;
    }

    public function getUserRole() {
        return $this->userRole;
    }

    public function getExpiryDate() {
        return $this->expires;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

}
