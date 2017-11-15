<?php

namespace AdminUserTest\Model\Service\AdminUser;

use AdminUser\Model\Service\AdminUser\Get;
use AdminUser\Model\DataContainer\AdminUser;
use PHPUnit_Framework_TestCase;
use AdminUserTest\AdminUserBootstrap;
use Zend\Db\ResultSet\ResultSet;

class GetTest extends PHPUnit_Framework_TestCase {
    protected $sm;

    public function setUp() {
        
        $this->sm = AdminUserBootstrap::getServiceManager();
        $this->setMocks();
    }

    public function testExecuteSuccess() {
        $AdminUser = new AdminUser([
            'id'              => 'dfs',
            'user_role'       => 'sdfds',
            'username'        => 'dsfsf',
            'email'           => '20015/03/03',
            'first_name'      => 'test',
            'last_name'       => 'test',
        ]);
        $rs   = new ResultSet();
        $rs->setArrayObjectPrototype($AdminUser);
        $rs->initialize([$AdminUser->getArrayCopy()]);

        $this->mockDataValidator->expects($this->at(0))
            ->method('setUp')
            ->will($this->returnValue(true));
        $this->mockDataValidator->expects($this->at(1))
            ->method('isValid')
            ->will($this->returnValue(true));
        $this->mockExecuter->expects($this->at(0))
            ->method('getAdapter');
        $this->mockExecuter->expects($this->at(1))
            ->method('execute')
            ->will($this->returnValue($rs));

        $getter = new Get($this->sm);
        $getter->execute([]);
        $this->assertEquals(
            $AdminUser->getArrayCopy(), $getter->getData()
        );
    }
    public function testExecuteFail() {
        $AdminUser = new AdminUser([
            'id'              => 'dfs',
            'user_role'       => 'sdfds',
            'username'        => 'dsfsf',
            'email'           => '20015/03/03',
            'first_name'      => 'test',
            'last_name'       => 'test'
        ]);
        $rs   = new ResultSet();
        $rs->setArrayObjectPrototype($AdminUser);
        $rs->initialize([$AdminUser->getArrayCopy()]);

        $this->mockDataValidator->expects($this->at(0))
            ->method('setUp');
        $this->mockDataValidator->expects($this->at(1))
            ->method('isValid')
            ->will($this->returnValue(false));
        $this->mockDataValidator->expects($this->at(1))
            ->method('getData')
            ->will($this->returnValue($AdminUser->getArrayCopy()));
        
        $getter = new Get($this->sm);
        $getter->execute([]);
        $this->assertEquals(
            false, $getter->execute([])
        );
    }

    private function setMocks() {

        $this->mockDataValidator = $this->getMockBuilder('Core\Form\DataValidator')
            ->setMethods(['isValid','setUp','getMessages','getData'])
            ->getMock();
        $this->mockExecuter      = $this->getMockBuilder('Core\Db\SqlExecuter')
            ->disableOriginalConstructor()
            ->setMethods(['execute', 'getAdapter'])
            ->getMock();
        $this->sm->setAllowOverride(true);

        $this->sm->setFactory(
            'ExecuterAdmin', function () {
            return $this->mockExecuter;
        });
        $this->sm->setFactory(
            'DataValidator', function () {
            return $this->mockDataValidator;
        });
    }

}
