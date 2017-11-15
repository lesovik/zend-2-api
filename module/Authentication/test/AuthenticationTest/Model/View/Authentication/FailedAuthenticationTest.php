<?php

namespace AuthenticationTest\Model\View\Authentication;

use Authentication\Model\Exception\Authentication\InvalidToken as Exception;
use Authentication\Model\View\Authentication\FailedAuthentication;


use Zend\Mvc\MvcEvent;
use Zend\Http\Response as Response;
/**
 * Json View generator
 *
 * @author Dmitry Lesov
 */
use PHPUnit_Framework_TestCase;

class FailedAuthenticationTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        
        $exception = new Exception();
        $event     = new MvcEvent();
        $response  = new Response();
        $event->setResponse($response);
        $view = new FailedAuthentication($event);
        $this->assertEquals(
            $exception->getMessage(), $view->getVariable('message')
        );
        $this->assertEquals(
            $exception->getCode(), $event->getResponse()->getStatusCode()
        );
    }


}
