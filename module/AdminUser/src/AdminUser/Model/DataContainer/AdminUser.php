<?php

/*
 * Zend 2 API
 */

namespace AdminUser\Model\DataContainer;

use Core\DataContainer;

/**
 * 
 *  AdminUser
 *
 * @author Dmitry Lesov
 */
class AdminUser extends DataContainer {

    protected $loginId        = null;
    protected $adminUserRole  = null;
    protected $adminUsername  = null;
    protected $password       = null;
    protected $email          = null;
    protected $loginStatus    = null;
    protected $firstName      = null;
    protected $lastName       = null;
    protected $avatarUrl      = null;
    protected static $keyMap  = [
        'loginId'       => 'id',
        'adminUsername' => 'username',
        'firstName'     => 'first_name',
        'lastName'      => 'last_name',
        'email'         => 'email',
        'adminUserRole' => 'user_role',
        'loginStatus'   => 'login_status',
        'avatarUrl'     => 'avatar_url',
    ];
    protected $sort           = 'id';
    protected $order          = 'ASC';
    protected $skip           = 0;
    protected $limit          = 20;

    /**
     * Getters
     */
    public function getLoginId() {
        return $this->loginId;
    }

    public function getUserRole() {
        return $this->adminUserRole;
    }

    public function getUsername() {
        return $this->adminUsername;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

 
    public function getLoginStatus() {
        return $this->loginStatus;
    }

  
    public function getAvatarUrl() {
        return $this->avatarUrl;
    }

    public function getSort() {
        return $this->sort;
    }

    public function getOrder() {
        return $this->order;
    }

    public function getSkip() {
        return $this->skip;
    }

    public function getLimit() {
        return $this->limit;
    }

    /**
     * Setters
     */
    public function setLoginId( $id ) {
        $this->loginId = $id;
    }

}
