<?php

/*
 * Zend 2 API
 */

namespace AdminUserTest\Model\Sql\Insert\AdminUser;

use Zend\Db\Sql\Insert as ZendInsert;
use AdminUser\Model\Sql\Insert\AdminUser\Login;
use AdminUser\Model\DataContainer\AdminUser;
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
        $adminUser         = new AdminUser([
            'email'     => 'email',
            'user_role' => 'role',
            'first_name' => 'role',
            'lsat_name' => 'role',
        ]);
        $sm           = AdminUserBootstrap::getServiceManager();
       
        $this->mockPm->expects($this->at(0))
            ->method('create_hash')
            ->will($this->returnValue('ku'));
        $sql          = new ZendInsert();
        $adapter      = $sm->get('dbAdminAdapter');

        $sql->into('login')
            ->values([
                'username'  => substr('123456789012345678901234567890', 18, 8),
                'email'     => $adminUser->getEmail(),
                'user_role' => $adminUser->getUserRole(),
                
            'first_name' => $adminUser->getFirstName(),
            'last_name' => $adminUser->getLastName(),
                'password'  => 'ku',
        ]);

        $insert = new DummyLoginOverride($adminUser,$this->mockPm);
        $this->assertEquals(
            $sql->getSqlString($adapter->getPlatform()), $insert->getSqlString($adapter->getPlatform())
        );
    }

}

class DummyLoginOverride extends Login {

    protected $rnd = '123456789012345678901234567890';

}

