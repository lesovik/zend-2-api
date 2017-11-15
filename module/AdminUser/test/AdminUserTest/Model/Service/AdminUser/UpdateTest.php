<?php

namespace AdminUserTest\Model\Service\AdminUser;

use AdminUser\Model\Service\AdminUser\Update;
use AdminUser\Model\DataContainer\AdminUser\ForUpdate as AdminUser;
use PHPUnit_Framework_TestCase;
use AdminUserTest\AdminUserBootstrap;
use Zend\Db\ResultSet\ResultSet;

class UpdateTest extends PHPUnit_Framework_TestCase {

    protected $sm;

    public function setUp() {

        $this->sm = AdminUserBootstrap::getServiceManager();
        $this->setMocks();
    }

    public function dataInserts() {
        return [
            [
                [
                    'id'           => 1,
                    'user_role'    => 'sdfds',
                    'username'     => 'dsfsf',
                    'email'        => '20015/03/03',
                    'first_name'   => 'test',
                    'last_name'    => 'test',
                    'login_status' => 'test'
                ]
            ],
            [
                [
                    [
                        'id'           => 50,
                        'user_role'    => 'sdfds',
                        'username'     => 'dsfsf',
                        'email'        => '20015/03/03',
                        'first_name'   => 'test',
                        'last_name'    => 'test',
                        'login_status' => 'test'
                    ],
                    [
                        'id'           => 200,
                        'user_role'    => 'sdfds',
                        'username'     => 'dsfsf',
                        'email'        => '20015/03/03',
                        'first_name'   => 'test',
                        'last_name'    => 'test',
                        'password'     => 'ku',
                        'login_status' => 'test'
                    ],
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataInserts
     * @param array $params
     */
    public function testExecuteSuccess( $params ) {
        $data = $params;
        if ( array_keys($params) !== range(0, count($params) - 1) ) {
            $data   = [$params];
            $params = [$params];
        }
        foreach ($data as $key => $datum) {
            $AdminUser    = new AdminUser($datum);
            $rs           = new ResultSet();
            $params[$key] = $AdminUser->getArrayCopy();

            $rs->setArrayObjectPrototype($AdminUser);
            $rs->initialize([$AdminUser->getArrayCopy()]);

            $this->mockAdminUserGet->expects($this->at(0))
                ->method('execute')
                ->will($this->returnValue(true));
            $this->mockAdminUserGet->expects($this->at(1))
                ->method('getData')
                ->will($this->returnValue([]));
            $this->mockDataValidator->expects($this->at(0))
                ->method('setUp');
            $this->mockDataValidator->expects($this->at(1))
                ->method('isValid')
                ->will($this->returnValue(true));

            $this->mockExecuter->expects($this->at(0))
                ->method('getAdapter')
                ->will($this->returnValue($this->mockAdapter));
            $this->mockExecuter->expects($this->at(1))
                ->method('getAdapter')
                ->will($this->returnValue($this->mockAdapter));
            $this->mockExecuter->expects($this->at(2))
                ->method('execute')
                ->will($this->returnValue($rs));

            $this->mockAdapter->expects($this->at(0))
                ->method('getDriver')
                ->will($this->returnValue($this->mockDriver));

            $this->mockDriver->expects($this->at(0))
                ->method('getConnection')
                ->will($this->returnValue($this->mockConnection));

            $this->mockConnection->expects($this->at(0))
                ->method('beginTransaction');
            $this->mockConnection->expects($this->at(1))
                ->method('commit');
        }
        $getter = new Update($this->sm);
        $getter->execute($data);

        if ( count($params) == 1 ) {
            $params = $params[0];
        }
        $this->assertEquals(
            $params, $getter->getData()
        );
    }

    public function testExecuteFail() {
        $this->setMocks();
        $AdminUser = new AdminUser();
        $rs        = new ResultSet();

        $rs->setArrayObjectPrototype($AdminUser);
        $rs->initialize([$AdminUser->getArrayCopy()]);
        $this->mockAdminUserGet->expects($this->at(0))
            ->method('execute')
            ->will($this->returnValue(true));
        $this->mockAdminUserGet->expects($this->at(1))
            ->method('getData');
        $this->mockDataValidator->expects($this->at(0))
            ->method('setUp');
        $this->mockDataValidator->expects($this->at(1))
            ->method('isValid')
            ->will($this->returnValue(false));
//        $this->mockDataValidator->expects($this->at(2))
//            ->method('getData');
        $this->mockExecuter->expects($this->at(0))
            ->method('getAdapter')
            ->will($this->returnValue($this->mockAdapter));
        $this->mockExecuter->expects($this->at(1))
            ->method('getAdapter')
            ->will($this->returnValue($this->mockAdapter));
        $this->mockAdapter->expects($this->at(0))
            ->method('getDriver')
            ->will($this->returnValue($this->mockDriver));
        $this->mockAdapter->expects($this->at(1))
            ->method('getDriver')
            ->will($this->returnValue($this->mockDriver));
        $this->mockDriver->expects($this->at(0))
            ->method('getConnection')
            ->will($this->returnValue($this->mockConnection));
        $this->mockDriver->expects($this->at(1))
            ->method('getConnection')
            ->will($this->returnValue($this->mockConnection));
        $this->mockConnection->expects($this->at(0))
            ->method('beginTransaction');
        $this->mockExecuter->expects($this->at(1))
            ->method('execute')
            ->will($this->returnValue($rs));
        $this->mockExecuter->expects($this->at(2))
            ->method('getAdapter')
            ->will($this->returnValue($this->mockAdapter));

        $getter = new Update($this->sm);
        $getter->execute([]);
        $this->assertEquals(
            false, $getter->execute([])
        );
    }

    private function setMocks() {

        $this->mockDataValidator = $this->getMockBuilder('Core\Form\DataValidator')
            ->setMethods(['isValid', 'setUp', 'getMessages', 'getData'])
            ->getMock();
        $this->mockExecuter      = $this->getMockBuilder('Core\Db\SqlExecuter')
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter', 'execute'])
            ->getMock();
        $this->mockConnection    = $this->getMockBuilder('Zend\Db\Adapter\Driver\Pgsql\Pgsql\Connection')
            ->disableOriginalConstructor()
            ->setMethods([
                'beginTransaction',
                'commit',
                'rollback'
            ])
            ->getMock();
        $this->mockDriver        = $this->getMockBuilder('Zend\Db\Adapter\Driver\Pgsql\Pgsql')
            ->disableOriginalConstructor()
            ->setMethods(['getConnection'])
            ->getMock();
        $this->mockAdapter       = $this->getMockBuilder('Zend\Db\Adapter\Adapter')
            ->disableOriginalConstructor()
            ->setMethods(['getDriver'])
            ->getMock();
        $this->mockAdminUserGet  = $this->getMockBuilder('AdminUser\Model\Service\AdminUser\Get')
            ->disableOriginalConstructor()
            ->setMethods(['execute', 'getData'])
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
        $this->sm->setFactory(
            'AdminUserGet', function () {
            return $this->mockAdminUserGet;
        });
    }

}
