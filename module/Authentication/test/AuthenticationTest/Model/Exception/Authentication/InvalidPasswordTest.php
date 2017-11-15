<?php

namespace AuthenticationTest\Model\Exception\Authentication;

use Authentication\Model\Exception\Authentication\InvalidPassword as Exception;
use Authentication\Model\Exception\Authentication as ParentException;
use PHPUnit_Framework_TestCase;
use Zend\Http\Response;

class InvalidPasswordTest extends PHPUnit_Framework_TestCase {

    public function testException() {
        $ex = new Exception();
        $this->assertEquals(ParentException::MESSAGE.Exception::MESSAGE,$ex->getMessage());
        $this->assertEquals(Response::STATUS_CODE_401,$ex->getCode());
    }

}
