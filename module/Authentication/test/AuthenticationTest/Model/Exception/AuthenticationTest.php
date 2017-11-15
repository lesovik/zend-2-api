<?php

namespace AuthenticationTest\Model\Exception;

use Authentication\Model\Exception\Authentication as Exception;
use PHPUnit_Framework_TestCase;

class AuthenticationTest extends PHPUnit_Framework_TestCase {

    public function testException() {
        $ex = new Exception('message',123);
        $this->assertEquals(Exception::MESSAGE."message",$ex->getMessage());
        $this->assertEquals(123,$ex->getCode());
    }

}
