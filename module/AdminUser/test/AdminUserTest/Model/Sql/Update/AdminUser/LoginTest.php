<?php

/*
 * Zend 2 API
 */

namespace AdminUserTest\Model\Sql\Update\AdminUser;

use Zend\Db\Sql\Update as ZendUpdate;
use AdminUser\Model\Sql\Update\AdminUser\Login;
use AdminUser\Model\DataContainer\AdminUser\ForUpdate as AdminUser;
use AdminUserTest\AdminUserBootstrap;
use PHPUnit_Framework_TestCase;

/**
 * 
 *
 * @author Dmitry Lesov
 */
class LoginTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $this->mockPm = $this->getMockBuilder('Authentication\Model\Service\PasswordManager')
            ->setMethods(['create_hash'])
            ->getMock();
        $adminUser    = new AdminUser([
            "login_status" => 'tcvvest',
            "username"     => 'tejklkst',
            "email"        => 'tewerst',
            "user_role"    => 'tefdgst',
            "first_name"   => 'tezxst',
            "last_name"    => 'tesulkiut',
            'password'     => 'ku',
            'garbage'      => 'ku',
            "id"           => 1
        ]);
        $sm           = AdminUserBootstrap::getServiceManager();

        $this->mockPm->expects($this->at(0))
            ->method('create_hash')
            ->will($this->returnValue('ku'));
        $sql     = new ZendUpdate();
        $adapter = $sm->get('dbAdminAdapter');

        $sql->table('login')
            ->set([
                'login_status' => $adminUser->getLoginStatus(),
                'username'     => $adminUser->getUsername(),
                'password'   => 'ku',
                'user_role'  => $adminUser->getUserRole(),
                'first_name' => $adminUser->getFirstName(),
                'last_name'  => $adminUser->getLastName(),
                'email'      => $adminUser->getEmail(),
        ]);
        $sql->where(['id' => $adminUser->getLoginId()]);
        $insert = new DummyLoginOverride($adminUser, $this->mockPm);
        $this->assertEquals(
            $sql->getSqlString($adapter->getPlatform()), $insert->getSqlString($adapter->getPlatform())
        );
    }

}

class DummyLoginOverride extends Login {

    protected $rnd = '123456789012345678901234567890';

}
