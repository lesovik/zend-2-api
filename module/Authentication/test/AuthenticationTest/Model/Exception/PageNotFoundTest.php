<?php

namespace AuthenticationTest\Model\Exception;

use Authentication\Model\Exception\PageNotFound as Exception;
use PHPUnit_Framework_TestCase;
use Zend\Http\Response;

class PageNotFoundTest extends PHPUnit_Framework_TestCase {

    public function testException() {
        $ex = new Exception();
        $this->assertEquals(Exception::MESSAGE,$ex->getMessage());
        $this->assertEquals(Response::STATUS_CODE_404,$ex->getCode());
    }

}
