<?php

namespace AuthenticationTest\Model\Service;

use Authentication\Model\Service\TokenStorage;
use Authentication\Model\DataContainer\AccessToken;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

class TokenStorageTest extends PHPUnit_Framework_TestCase {

    private $data;
    public function setUp() {
        $this->mockRequest = $this->getMockBuilder('Zend\Http\PhpEnvironment\Request')
            ->disableOriginalConstructor()
            ->setMethods(['getHeader'])
            ->getMock();
        $this->mockHeader  = $this->getMockBuilder('Zend\Http\Header\Authorization')
            ->disableOriginalConstructor()
            ->setMethods(['getFieldValue'])
            ->getMock();
        $this->mockExec    = $this->getMockBuilder('Core\Db\SqlExecuter')
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();
        $this->data        = [
            'login_id' => 'dfs',
            'token'    => 'sdfds',
            'ip'       => 'dsfsf',
            'expires'  => '20015/03/03',
            'id'       => null,
        ];
    }

    public function testRead() {

        $token = new AccessToken($this->data);

        $rs = new ResultSet();
        $rs->setArrayObjectPrototype($token);
        $rs->initialize([$this->data]);
        $this->mockRequest->expects($this->at(0))
            ->method('getHeader')
            ->will($this->returnValue($this->mockHeader));
        $this->mockExec->expects($this->at(0))
            ->method('execute')
            ->will($this->returnValue($rs));
        $this->mockHeader->expects($this->at(0))
            ->method('getFieldValue')
            ->will($this->returnValue('Bearer test'));
        $this->mockHeader->expects($this->at(1))
            ->method('getFieldValue')
            ->will($this->returnValue('Bearer test'));
       

        $storage = new TokenStorage($this->mockRequest, $this->mockExec);
        $actual  = $storage->read();
        $this->assertEquals($token, $actual);
        $this->assertEquals(
            $token->getArrayCopy(), $actual->getArrayCopy()
        );
    }

    public function testIsEmpty() {

        $token = new AccessToken($this->data);

        $rs = new ResultSet();
        $rs->setArrayObjectPrototype($token);
        $rs->initialize([$this->data]);
        $this->mockRequest->expects($this->at(0))
            ->method('getHeader')
            ->will($this->returnValue($this->mockHeader));
        $this->mockExec->expects($this->at(0))
            ->method('execute')
            ->will($this->returnValue($rs));
        $this->mockHeader->expects($this->at(0))
            ->method('getFieldValue')
            ->will($this->returnValue('Bearer test'));

        $storage = new TokenStorage($this->mockRequest, $this->mockExec);

        $this->assertEquals(false, $storage->isEmpty());
    }

    public function testReadFalse() {

        $rs =[];
        $this->mockRequest->expects($this->at(0))
            ->method('getHeader')
            ->will($this->returnValue($this->mockHeader));
        $this->mockExec->expects($this->at(0))
            ->method('execute')
            ->will($this->returnValue($rs));
        $this->mockHeader->expects($this->at(0))
            ->method('getFieldValue')
            ->will($this->returnValue('Bearer test'));

        $storage = new TokenStorage($this->mockRequest, $this->mockExec);
        $actual  = $storage->read();
        $this->assertEquals(false, $actual);
    }

    public function testSetIdentity() {
        $storage = new TokenStorage($this->mockRequest, $this->mockExec);
        $loginId = 'test';
        $this->mockExec->expects($this->at(0))
            ->method('execute');
        $storage->setIdentity($loginId);
        $actual  = $storage->getToken();
        $this->assertEquals($loginId, $actual->getLoginId());
    }

    public function testClear() {
        $storage = new TokenStorage($this->mockRequest, $this->mockExec);
        $this->mockExec->expects($this->at(0))
            ->method('execute');
        $storage->setIdentity('test');
        $storage->clear();
        $this->assertEquals(null, $storage->getToken());
    }

}
