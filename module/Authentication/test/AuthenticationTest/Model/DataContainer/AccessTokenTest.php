<?php

namespace AuthenticationTest\Model\DataContainer;

use Authentication\Model\DataContainer\AccessToken;
use PHPUnit_Framework_TestCase;

class AccessTokenTest extends PHPUnit_Framework_TestCase {

    public function testContainer() {
        $crit  = [
            'login_id' => 'dfs',
            'token'    => 'sdfds',
            'ip'       => 'dsfsf',
            'expires'  => 'dfsds',
            'id'  => null,
            'user_role'  => null,
            'first_name'  => null,
            'last_name'  => null,
        ];
        $token = new AccessToken($crit);
        $this->assertEquals($crit['login_id'], $token->getLoginId());
        $this->assertEquals($crit['token'], $token->getToken());
        $this->assertEquals($crit['ip'], $token->getIp());
        $this->assertEquals($crit['expires'], $token->getExpiryDate());
        $this->assertEquals($crit, $token->getArrayCopy());
    }

}
