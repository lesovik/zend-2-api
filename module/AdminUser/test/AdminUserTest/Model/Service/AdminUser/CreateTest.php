<?php

namespace AdminUserTest\Model\Service\AdminUser;

use AdminUser\Model\Service\AdminUser\Create;
use AdminUser\Model\DataContainer\AdminUser\ForInsert as AdminUser;
use PHPUnit_Framework_TestCase;
use AdminUserTest\AdminUserBootstrap;
use Zend\Db\ResultSet\ResultSet;

class CreateTest extends PHPUnit_Framework_TestCase {

    protected $sm;

    public function setUp() {

        $this->sm = AdminUserBootstrap::getServiceManager();
        $this->setMocks();
    }

    public function dataInserts() {
        return [
            [
                [
                    'first_name'   => 'sdfds',
                    'last_name'    => '20015/03/03',
                    'email'        => 'test',
                    'company_name' => 'test',
                    'user_role'    => true
                ]
            ],
            [
                [
                    [
                        'first_name'   => 'sdfds',
                        'last_name'    => '20015/03/03',
                        'email'        => 'test',
                        'user_role'    => true
                    ],
                    [
                        'first_name'   => 'sdfds',
                        'last_name'    => '20015/03/03',
                        'email'        => 'test',
                        'company_name' => 'test',
                        'user_role'    => true
                    ]
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
            $params   = [$params];
        }
        foreach ($params as $key => $datum) {
            $AdminUser         = new AdminUser($datum);
           
            $rs           = new ResultSet();
            $params[$key] = $AdminUser->getArrayCopy();


            $rs->setArrayObjectPrototype($AdminUser);
            $rs->initialize([$AdminUser->getArrayCopy()]);
             $this->mockDataValidator->expects($this->at(0))
                ->method('isValid')
                ->will($this->returnValue(true));

            $this->mockExecuter->expects($this->at(0))
                ->method('getAdapter')
                ->will($this->returnValue($this->mockAdapter));
            $this->mockExecuter->expects($this->at(1))
                ->method('getAdapter')
                ->will($this->returnValue($this->mockAdapter));


         

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
        $getter = new Create($this->sm);
        $getter->execute($params);
        if ( count($params) == 1 ) {
            $params = $params[0];
        }
        $this->assertEquals(
            $params, $getter->getData()
        );
    }
    /**
     * @dataProvider dataInserts
     * @param array $params
     */
    public function testExecuteFail( $params ) {
        $data = $params;
        if ( array_keys($params) !== range(0, count($params) - 1) ) {
            $params   = [$params];
        }
        foreach ($params as $key => $datum) {
            $AdminUser         = new AdminUser($datum);
           
            $rs           = new ResultSet();
            $params[$key] = $AdminUser->getArrayCopy();


            $rs->setArrayObjectPrototype($AdminUser);
            $rs->initialize([$AdminUser->getArrayCopy()]);
             $this->mockDataValidator->expects($this->at(0))
                ->method('isValid')
                ->will($this->returnValue(false));

            $this->mockExecuter->expects($this->at(0))
                ->method('getAdapter')
                ->will($this->returnValue($this->mockAdapter));
            $this->mockExecuter->expects($this->at(1))
                ->method('getAdapter')
                ->will($this->returnValue($this->mockAdapter));


         

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
        $getter = new Create($this->sm);
        $getter->execute($params);
        if ( count($params) == 1 ) {
            $params = $params[0];
        }
        $this->assertEquals(
            $params, $getter->getData()
        );
    }

    private function setMocks() {

        $this->mockDataValidator = $this->getMockBuilder('Core\Form\DataValidator')
            ->setMethods(['isValid', 'setUp', 'getMessages', 'getData'])
            ->getMock();
        $this->mockExecuter      = $this->getMockBuilder('Core\Db\SqlExecuter')
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter', 'execute', 'lastGeneratedValue'])
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
