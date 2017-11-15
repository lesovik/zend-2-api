<?php

namespace ActionLogTest\Model\Service\ActionLog;

use ActionLog\Model\Service\ActionLog\GetList;
use ActionLog\Model\DataContainer\ActionLog;
use PHPUnit_Framework_TestCase;
use ActionLogTest\ActionLogBootstrap;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Paginator as ZendPaginator;
use Zend\Paginator\Adapter\ArrayAdapter;

class GetListTest extends PHPUnit_Framework_TestCase {

    protected $sm;

    public function setUp() {

        $this->sm = ActionLogBootstrap::getServiceManager();
        $this->setMocks();
    }

    public function testExecuteSuccess() {
        $ActionLog = new ActionLog([
            'id'              => 'dfs',
            'ActionLog_role'       => 'sdfds',
            'ActionLogname'        => 'dsfsf',
            'email'           => '20015/03/03',
            'first_name'      => 'test',
            'last_name'       => 'test',
            'department'      => 'test',
            'invite_complete' => true,
            'company_name'    => 'test',
            'company_id'      => 45,
            'company_role'    => 'test',
            'city'            => 'test',
            'sort'            => 'id',
            'order'           => 'ASC',
            'skip'            => 1,
            'limit'           => 10,
        ]);
        $rs   = new ResultSet();
        $rs->setArrayObjectPrototype($ActionLog);
        $rs->initialize([$ActionLog->getArrayCopy()]);

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
        $paginator = new ZendPaginator(new ArrayAdapter([$ActionLog->getArrayCopy()]));
        $paginator->setCurrentPageNumber($ActionLog->getSkip()+1);
        $paginator->setItemCountPerPage($ActionLog->getLimit());
        $getter    = new GetList($this->sm);
        $getter->execute([]);
        $this->assertEquals(
            $paginator, $getter->getData()
        );
    }

    public function testExecuteFail() {
        $ActionLog = new ActionLog([
            'id'              => 'dfs',
            'ActionLog_role'       => 'sdfds',
            'ActionLogname'        => 'dsfsf',
            'email'           => '20015/03/03',
            'first_name'      => 'test',
            'last_name'       => 'test',
            'department'      => 'test',
            'invite_complete' => true,
            'company_name'    => 'test',
            'company_id'      => 45,
            'company_role'    => 'test',
            'city'            => 'test',
        ]);
        $rs   = new ResultSet();
        $rs->setArrayObjectPrototype($ActionLog);
        $rs->initialize([$ActionLog->getArrayCopy()]);
        $this->mockDataValidator->expects($this->at(0))
            ->method('setUp');
        $this->mockDataValidator->expects($this->at(1))
            ->method('isValid')
            ->will($this->returnValue(false));
        $this->mockDataValidator->expects($this->at(1))
            ->method('getData')
            ->will($this->returnValue($ActionLog->getArrayCopy()));

        $getter = new GetList($this->sm);
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
