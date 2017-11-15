<?php

namespace ActionLogTest\Model\Service\ActionLog;

use ActionLog\Model\Service\ActionLog\Create;
use ActionLog\Model\DataContainer\ActionLog\ForInsert as ActionLog;
use PHPUnit_Framework_TestCase;
use ActionLogTest\ActionLogBootstrap;
use Zend\Db\ResultSet\ResultSet;

class CreateTest extends PHPUnit_Framework_TestCase {

    protected $sm;

    public function setUp() {

        $this->sm = ActionLogBootstrap::getServiceManager();
        $this->setMocks();
    }

    public function dataInserts() {
        return [
            [
                [
                    'token_id'      => 'sdfds',
                    'method'        => '20015/03/03',
                    'route'         => 'test',
                    'data'          => [
                        'test'     => 'gsgsgs',
                        'password' => 'gsgsgs'
                    ],
                    'response_code' => 'test',
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataInserts
     * @param array $params
     */
    public function testExecuteSuccess( $params ) {

        $serialized         = $params;
        $serialized['data'] = serialize($params['data']);
        $ActionLog          = new ActionLog($serialized);

        $rs = new ResultSet();
        $rs->setArrayObjectPrototype($ActionLog);
        $rs->initialize([$ActionLog->getArrayCopy()]);
        $this->mockDataValidator->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->mockExecuter->expects($this->at(0))
            ->method('getAdapter')
            ->will($this->returnValue($this->mockAdapter));
        $this->mockExecuter->expects($this->at(1))
            ->method('execute');

        $getter                     = new Create($this->sm);
        $getter->execute($params);
        $this->assertEquals(
            $params, $getter->getData()
        );
    }

    /**
     * @dataProvider dataInserts
     * @param array $params
     */
    public function testExecuteFail( $params ) {

        $ActionLog = new ActionLog($params);

        $rs = new ResultSet();


        $rs->setArrayObjectPrototype($ActionLog);
        $rs->initialize([$ActionLog->getArrayCopy()]);
        $this->mockDataValidator->expects($this->at(0))
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->mockExecuter->expects($this->at(0))
            ->method('getAdapter')
            ->will($this->returnValue($this->mockAdapter));

        $getter = new Create($this->sm);

        $this->assertFalse($getter->execute($params));
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
