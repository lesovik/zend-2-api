<?php

/*
 * Zend 2 API
 */

namespace AuthenticationTest\Model\Sql\Select;

use Zend\Db\Sql\Select as ZendSelect;
use Authentication\Model\Sql\Select\AccessToken as Select;
use Authentication\Model\DataContainer\Criteria\AccessToken as Criteria;
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

        $token   = new Criteria([
            'token' => 'sdfds',
        ]);
        $sql     = new ZendSelect();
        $adapter = $sm->get('dbAdminAdapter');
        $sql
            ->from('access_token')
            ->columns([
                'id',
                'login_id',
                'token',
                'ip',
                'expires',
            ])
            ->join('login','access_token.login_id=login.id',[
                'user_role',
                'first_name',
                'last_name'
            ])
            ->where([
                'token' => $token->getToken()
            ])
        ;
        $select  = new Select($token);
        $this->assertEquals(
            $sql->getSqlString($adapter->getPlatform()),
            $select->getSqlString($adapter->getPlatform())
        );
    }
}