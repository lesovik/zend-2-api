<?php

namespace AdminUserTest\Model\DataContainer;

use AdminUser\Model\DataContainer\AdminUser;
use PHPUnit_Framework_TestCase;

class AdminUserTest extends PHPUnit_Framework_TestCase {

    public function testContainer() {
        $crit  = [
            'id' => 'dfs',
            'username'    => 'sdfds',
            'first_name'       => 'dsfsf',
            'last_name'  => 'dfsds',
            'email'  => 'nudsfll',
            'user_role'  => 'null',
            'login_status'  => 'nusdfsll',
           
            'avatar_url'  => 'nusdfll',
        ];
        
        $AdminUser = new AdminUser($crit);
        $this->assertEquals($crit['id'], $AdminUser->getLoginId());
        $this->assertEquals($crit['username'], $AdminUser->getUsername());
        $this->assertEquals($crit['first_name'], $AdminUser->getFirstName());
        $this->assertEquals($crit['last_name'], $AdminUser->getLastName());
        $this->assertEquals($crit['email'], $AdminUser->getEmail());
        $this->assertEquals($crit['user_role'], $AdminUser->getUserRole());
        $this->assertEquals($crit['login_status'], $AdminUser->getLoginStatus());
        $this->assertEquals($crit, $AdminUser->getArrayCopy());
    }

}
