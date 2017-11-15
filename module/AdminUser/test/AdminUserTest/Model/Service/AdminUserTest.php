<?php

namespace AdminUserTest\Model\Service;

use AdminUser\Model\Service\AdminUser;
use PHPUnit_Framework_TestCase;
use AdminUserTest\AdminUserBootstrap;

class AdminUserTest extends PHPUnit_Framework_TestCase {

    public function testAdminUser() {
        $sm   = AdminUserBootstrap::getServiceManager();
        $AdminUser = new AdminUserTestDummy($sm);
        $this->assertAttributeInstanceOf('Core\Db\SqlExecuter','executer',$AdminUser);
        $this->assertAttributeInstanceOf('Authentication\Model\Service\PasswordManager','passwordManager',$AdminUser);
        $this->assertAttributeInstanceOf('Core\Form\DataValidator','validator',$AdminUser);
        $this->assertAttributeInstanceOf('Zend\ServiceManager\ServiceManager','serviceManager',$AdminUser);
    }

}

class AdminUserTestDummy extends AdminUser {

   

    public function execute( array $parameters ) {
        
    }
}
