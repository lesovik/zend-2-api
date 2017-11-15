<?php

namespace AuthenticationTest\Model\View;

use Core\Exception;
use Authentication\Model\View\Authentication;
use Authentication\Model\DataContainer\AccessToken;
use Zend\Mvc\MvcEvent;
use Zend\Http\Response as Response;
use PHPUnit_Framework_TestCase;

/**
 * Json View generator
 *
 * @author Dmitry Lesov
 */

class AuthenticationTest extends PHPUnit_Framework_TestCase {

    protected $view;

    public function setUp() {
        $this->view = new Authentication();
    }

    public function testSetFail() {
        $exception = new Exception('message', Response::STATUS_CODE_404);
        $event     = new MvcEvent();
        $response  = new Response();
        $event->setResponse($response);
        $this->view->setFail($exception, $event);
        $this->assertEquals(
            $exception->getMessage(), $this->view->getVariable('message')
        );
        $this->assertEquals(
            $exception->getCode(), $event->getResponse()->getStatusCode()
        );
    }

    public function testSetSussess() {
        $token     = new AccessToken([
            'login_id' => 'dfs',
            'token'    => 'sdfds',
            'ip'       => 'dsfsf',
            'expires'  => '20015/03/03',
            'id'       => null,
        ]);
        $this->view->setSuccess($token);
        $this->assertEquals(
            $token->getToken(), $this->view->getVariable('accessToken')
        );
        $this->assertEquals(
            strtotime($token->getExpiryDate()),
            $this->view->getVariable('accessTokenExp')
        );
    }

}
