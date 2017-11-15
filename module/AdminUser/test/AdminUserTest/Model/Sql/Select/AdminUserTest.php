<?php

/*
 * Zend 2 API
 */

namespace AdminUserTest\Model\Sql\Select;

use Zend\Db\Sql\Select as ZendSelect;
use AdminUser\Model\Sql\Select\AdminUser as Select;
use AdminUserTest\AdminUserBootstrap;
use PHPUnit_Framework_TestCase;

/**
 * 
 *
 * @author Dmitry Lesov
 */
class AdminUserTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $sm = AdminUserBootstrap::getServiceManager();


        $sql     = new ZendSelect();
        $adapter = $sm->get('dbAdminAdapter');
        $sql
            ->from(['login' => 'login'])
            ->columns([
                'id',
                'last_modified',
                'login_status',
                'user_role',
                'created',
                'username',
                'first_name',
                'last_name',
                'email',
            ])

        ;
        $select = new Select();
        $this->assertEquals(
            $sql->getSqlString($adapter->getPlatform()), $select->getSqlString($adapter->getPlatform())
        );
    }

}
