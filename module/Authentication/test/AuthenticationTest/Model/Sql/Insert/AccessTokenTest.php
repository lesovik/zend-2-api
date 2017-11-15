<?php

/*
 * Zend 2 API
 */

namespace AuthenticationTest\Model\Sql\Insert;

use Zend\Db\Sql\Insert as ZendInsert;
use Authentication\Model\Sql\Insert\AccessToken as Insert;
use Authentication\Model\DataContainer\AccessToken;
use AuthenticationTest\AuthenticationBootstrap as AuthenticationBootstrap;
use PHPUnit_Framework_TestCase;

/**
 * 
 *  AccessToken insert query
 *
 * @author Dmitry Lesov
 */
class AccessTokenTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $sm = AuthenticationBootstrap::getServiceManager();

        $token   = new AccessToken([
            'login_id' => 'dfs',
            'token'    => 'sdfds',
            'ip'       => 'dsfsf',
            'expires'  => '20015/03/03',
            'id'       => null,
        ]);
        $sql     = new ZendInsert();
        $adapter = $sm->get('dbAdminAdapter');
        $sql
            ->into('access_token')
            ->values([
                'login_id' => $token->getLoginId(),
                'token'    => $token->getToken(),
                'ip'       => $token->getIp(),
                'expires'  => $token->getExpiryDate(),
        ]);
        $insert  = new Insert($token);
        $this->assertEquals(
            $sql->getSqlString($adapter->getPlatform()),
            $insert->getSqlString($adapter->getPlatform()));
    }

}
