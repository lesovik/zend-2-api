<?php

namespace AuthenticationTest\Model\Exception\Authentication;

use Authentication\Model\Exception\Authentication\Uncategorized as Exception;
use Authentication\Model\Exception\Authentication as ParentException;
use PHPUnit_Framework_TestCase;
use Zend\Http\Response;

class UncategorizedTest extends PHPUnit_Framework_TestCase {

    public function testException() {

        $ex = new Exception();
        $this->assertEquals(ParentException::MESSAGE.'uncategorized',$ex->getMessage());
        $this->assertEquals(Response::STATUS_CODE_400,$ex->getCode());
    }

}
