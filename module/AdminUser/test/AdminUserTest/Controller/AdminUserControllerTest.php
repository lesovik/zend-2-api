<?php

namespace AdminUserTest\Controller;

use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Http\Response;
use Core\Test\PHPUnit\Controller\ControllerTestCase;
use AdminUserTest\AdminUserBootstrap;
use AdminUser\Controller\AdminUserController;
use Zend\Stdlib\Parameters;
use Zend\View\Model\JsonModel;
use Zend\Http\Headers;

class AdminUserControllerTest extends ControllerTestCase {

    /** @var ServiceManager */
    protected $sm;

    /** @var Request */
    protected $request;

    /** @var RouteMatch */
    protected $routeMatch;

    /** @var MvcEvent */
    protected $event;
    protected $controller;
    protected $mockService;
    protected $mockAdapter;
    protected $mockStorage;
    protected $mockToken;

    protected function setUp() {

        $this->sm         = AdminUserBootstrap::getServiceManager();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(['controller' => 'admin-users']);
        $this->event      = new MvcEvent();
        $this->event->setApplication(AdminUserBootstrap::getApplication());
        $this->setRequestTypeToHttp($this->sm);
        $this->setApplicationConfig($this->sm->get('ApplicationConfig'));
        $this->controller = new AdminUserController(new JsonModel());
        $this->setUpController($this->controller);
        $this->setMocks();
    }

    public function testGetSuccess() {
        $expected = [
            'test'  => 'test',
            'test2' => 'test2',
            'test3' => [
                'test4',
                'test5'
            ],
        ];
        $this->mockGet->expects($this->at(0))
            ->method('execute')
            ->will($this->returnValue(true));
        $this->mockGet->expects($this->at(1))
            ->method('getData')
            ->will($this->returnValue($expected));

        $this->routeMatch->setParam('id', '1');

        $this->request->setMethod(Request::METHOD_GET);
        $params = new Parameters(['id' => 1]);
        $this->request->setQuery($params);


        $result = $this->controller->dispatch($this->request);
        $this->assertInstanceOf(
            'Zend\View\Model\ViewModel', $result
        );
        foreach ($expected as $key => $val) {
            $this->assertEquals(
                $val, $result->getVariable($key)
            );
        }
    }

    public function testMeAction() {
        $expected = [
            'test'  => 'test',
            'test2' => 'test2',
            'test3' => [
                'test4',
                'test5'
            ],
        ];
        $this->mockService->expects($this->at(0))
            ->method('getStorage')
            ->will($this->returnValue($this->mockStorage));
        $this->mockStorage->expects($this->at(0))
            ->method('getToken')
            ->will($this->returnValue($this->mockToken));
        $this->mockToken->expects($this->at(0))
            ->method('getLoginId')
            ->will($this->returnValue(1));
        $this->mockGet->expects($this->at(0))
            ->method('execute')
            ->will($this->returnValue(true));
        $this->mockGet->expects($this->at(1))
            ->method('getData')
            ->will($this->returnValue($expected));

        $this->routeMatch = new RouteMatch(['controller' => 'admin-users', 'action' => 'me']);
        $this->event->setRouteMatch($this->routeMatch);

        $this->request->setMethod(Request::METHOD_GET);


        $result = $this->controller->dispatch($this->request);
        $this->assertInstanceOf(
            'Zend\View\Model\ViewModel', $result
        );
        foreach ($expected as $key => $val) {
            $this->assertEquals(
                $val, $result->getVariable($key)
            );
        }
    }

    public function testGetFail() {
        $this->mockGet->expects($this->at(0))
            ->method('execute')
            ->will($this->returnValue(false));


        $this->routeMatch->setParam('id', '1');

        $this->request->setMethod(Request::METHOD_GET);
        $this->request->setHeaders(Headers::fromString('Authorization: Bearer test'));
        $params = new Parameters(['id' => 1]);
        $this->request->setQuery($params);


        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertInstanceOf(
            'Zend\View\Model\ViewModel', $result
        );

        $this->assertEquals(
            Response::STATUS_CODE_404, $response->getStatusCode()
        );
    }

    public function testGetListSuccess() {
        $expected = [
            'test'  => 'test',
            'test2' => 'test2',
            'test3' => [
                'test4',
                'test5'
            ],
        ];
        $this->mockGetList->expects($this->at(0))
            ->method('execute')
            ->will($this->returnValue(true));
        $this->mockGetList->expects($this->at(1))
            ->method('getData')
            ->will($this->returnValue($expected));

        $this->request->setMethod(Request::METHOD_GET);
        $params = new Parameters();
        $this->request->setQuery($params);


        $result = $this->controller->dispatch($this->request);
        $this->assertInstanceOf(
            'Zend\View\Model\ViewModel', $result
        );
        foreach ($expected as $key => $val) {
            $results = $result->getVariable('results');
            $this->assertEquals(
                $val, $results[$key]
            );
        }
    }

    public function testGetListFail() {
        $data     = ['data'];
        $messages = ['messages'];
        $this->mockGetList->expects($this->at(0))
            ->method('execute')
            ->will($this->returnValue(false));
        $this->mockGetList->expects($this->at(1))
            ->method('getValidator')
            ->will($this->returnValue($this->mockDataValidator));
        $this->mockGetList->expects($this->at(2))
            ->method('getValidator')
            ->will($this->returnValue($this->mockDataValidator));
        $this->mockDataValidator->expects($this->at(0))
            ->method('getData')
            ->will($this->returnValue($data));
        $this->mockDataValidator->expects($this->at(1))
            ->method('getMessages')
            ->will($this->returnValue($messages));



        $this->request->setMethod(Request::METHOD_GET);



        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertInstanceOf(
            'Zend\View\Model\ViewModel', $result
        );

        $this->assertEquals(
            Response::STATUS_CODE_400, $response->getStatusCode()
        );
        $this->assertEquals(
            $data, $result->getVariable('data')
        );
        $this->assertEquals(
            $messages, $result->getVariable('errors')
        );
    }

    public function testCreateSuccess() {
        $expected = [
            'test'  => 'test',
            'test2' => 'test2',
            'test3' => [
                'test4',
                'test5'
            ],
        ];
        $this->mockCreate->expects($this->at(0))
            ->method('execute')
            ->will($this->returnValue(true));
        $this->mockCreate->expects($this->at(1))
            ->method('getData')
            ->will($this->returnValue($expected));

        $this->request->setMethod(Request::METHOD_POST);



        $result = $this->controller->dispatch($this->request);
        $this->assertInstanceOf(
            'Zend\View\Model\ViewModel', $result
        );
        foreach ($expected as $key => $val) {
            $this->assertEquals(
                $val, $result->getVariable($key)
            );
        }
    }

    public function testCreateFail() {
        $data     = ['data'];
        $messages = ['messages'];
        $this->mockCreate->expects($this->at(0))
            ->method('execute')
            ->will($this->returnValue(false));
        $this->mockCreate->expects($this->at(1))
            ->method('getValidator')
            ->will($this->returnValue($this->mockDataValidator));
        $this->mockCreate->expects($this->at(2))
            ->method('getValidator')
            ->will($this->returnValue($this->mockDataValidator));
        $this->mockDataValidator->expects($this->at(0))
            ->method('getData')
            ->will($this->returnValue($data));
        $this->mockDataValidator->expects($this->at(1))
            ->method('getMessages')
            ->will($this->returnValue($messages));



        $this->request->setMethod(Request::METHOD_POST);



        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertInstanceOf(
            'Zend\View\Model\ViewModel', $result
        );

        $this->assertEquals(
            Response::STATUS_CODE_400, $response->getStatusCode()
        );
        $this->assertEquals(
            $data, $result->getVariable('data')
        );
        $this->assertEquals(
            $messages, $result->getVariable('errors')
        );
    }

    public function testUpdateSuccess() {
        $expected = [
            'test'  => 'test',
            'test2' => 'test2',
            'test3' => [
                'test4',
                'test5'
            ],
        ];
        $this->mockUpdate->expects($this->at(0))
            ->method('execute')
            ->will($this->returnValue(true));
        $this->mockUpdate->expects($this->at(1))
            ->method('getData')
            ->will($this->returnValue($expected));

        $this->routeMatch->setParam('id', '1');

        $this->request->setMethod(Request::METHOD_PUT);
        $params = new Parameters(['id' => 1]);
        $this->request->setQuery($params);



        $result = $this->controller->dispatch($this->request);
        $this->assertInstanceOf(
            'Zend\View\Model\ViewModel', $result
        );
        foreach ($expected as $key => $val) {
            $this->assertEquals(
                $val, $result->getVariable($key)
            );
        }
    }

    public function testUpdateFail() {
        $data     = ['data'];
        $messages = ['messages'];
        $this->mockUpdate->expects($this->at(0))
            ->method('execute')
            ->will($this->returnValue(false));
        $this->mockUpdate->expects($this->at(1))
            ->method('getValidator')
            ->will($this->returnValue($this->mockDataValidator));
        $this->mockUpdate->expects($this->at(2))
            ->method('getValidator')
            ->will($this->returnValue($this->mockDataValidator));
        $this->mockDataValidator->expects($this->at(0))
            ->method('getData')
            ->will($this->returnValue($data));
        $this->mockDataValidator->expects($this->at(1))
            ->method('getMessages')
            ->will($this->returnValue($messages));



        $this->routeMatch->setParam('id', '1');

        $this->request->setMethod(Request::METHOD_PUT);
        $params = new Parameters(['id' => 1]);
        $this->request->setQuery($params);



        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertInstanceOf(
            'Zend\View\Model\ViewModel', $result
        );

        $this->assertEquals(
            Response::STATUS_CODE_400, $response->getStatusCode()
        );
        $this->assertEquals(
            $data, $result->getVariable('data')
        );
        $this->assertEquals(
            $messages, $result->getVariable('errors')
        );
    }

    public function testReplaceListSuccess() {
        $expected = [
            'test'  => 'test',
            'test2' => 'test2',
            'test3' => [
                'test4',
                'test5'
            ],
        ];
        $this->mockUpdate->expects($this->at(0))
            ->method('execute')
            ->will($this->returnValue(true));
        $this->mockUpdate->expects($this->at(1))
            ->method('getData')
            ->will($this->returnValue($expected));

        //$this->routeMatch->setParam('id', '1');

        $this->request->setMethod(Request::METHOD_PUT);




        $result = $this->controller->dispatch($this->request);
        $this->assertInstanceOf(
            'Zend\View\Model\ViewModel', $result
        );
        foreach ($expected as $key => $val) {
            $this->assertEquals(
                $val, $result->getVariable($key)
            );
        }
    }

    public function testReplaceListFail() {
        $data     = ['data'];
        $messages = ['messages'];
        $this->mockUpdate->expects($this->at(0))
            ->method('execute')
            ->will($this->returnValue(false));
        $this->mockUpdate->expects($this->at(1))
            ->method('getValidator')
            ->will($this->returnValue($this->mockDataValidator));
        $this->mockUpdate->expects($this->at(2))
            ->method('getValidator')
            ->will($this->returnValue($this->mockDataValidator));
        $this->mockDataValidator->expects($this->at(0))
            ->method('getData')
            ->will($this->returnValue($data));
        $this->mockDataValidator->expects($this->at(1))
            ->method('getMessages')
            ->will($this->returnValue($messages));


        $this->request->setMethod(Request::METHOD_PUT);




        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertInstanceOf(
            'Zend\View\Model\ViewModel', $result
        );

        $this->assertEquals(
            Response::STATUS_CODE_400, $response->getStatusCode()
        );
        $this->assertEquals(
            $data, $result->getVariable('data')
        );
        $this->assertEquals(
            $messages, $result->getVariable('errors')
        );
    }

    private function setUpController( AdminUserController $controller ) {
        $config       = $this->sm->get('Config');
        $routerConfig = $config['router'] ? : [];
        $router       = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);

        $controller->setEvent($this->event);
        $controller->setServiceLocator($this->sm);
    }

    private function setMocks() {

        $this->mockGet           = $this->getMockBuilder('AdminUser\Model\Service\AdminUser\Get')
            ->setConstructorArgs([$this->sm])
            ->setMethods(['execute', 'getData'])
            ->getMock();
        $this->mockDataValidator = $this->getMockBuilder('Core\Form\DataValidator')
            ->setMethods(['getData', 'getMessages'])
            ->getMock();
        $this->mockGetList       = $this->getMockBuilder('AdminUser\Model\Service\AdminUser\GetList')
            ->setConstructorArgs([$this->sm])
            ->setMethods(['execute', 'getData', 'getValidator'])
            ->getMock();
        ;
        $this->mockCreate        = $this->getMockBuilder('AdminUser\Model\Service\AdminUser\Create')
            ->setConstructorArgs([$this->sm])
            ->setMethods(['execute', 'getData', 'getValidator'])
            ->getMock();
        ;
        $this->mockUpdate        = $this->getMockBuilder('AdminUser\Model\Service\AdminUser\Update')
            ->setConstructorArgs([$this->sm])
            ->setMethods(['execute', 'getData', 'getValidator'])
            ->getMock();
        ;
        $this->mockService       = $this->getMockBuilder('Zend\Authentication\AuthenticationService')
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter', 'getStorage', 'authenticate'])
            ->getMock();

       

        $this->mockStorage = $this->getMockBuilder('Authentication\Model\Service\TokenStorage')
            ->disableOriginalConstructor()
            ->setMethods(['setIdentity', 'getToken'])
            ->getMock();

        $this->mockToken = $this->getMockBuilder('Authentication\Model\DataContainer\AccessToken')
            ->disableOriginalConstructor()
            ->setMethods(['getToken','getLoginId'])
            ->getMock();
        $this->sm->setAllowOverride(true);
        $this->sm->setFactory(
            'AdminUserGet', function () {
            return $this->mockGet;
        });

        $this->sm->setFactory(
            'AdminUserGetList', function () {
            return $this->mockGetList;
        });
        $this->sm->setFactory(
            'AdminUserCreate', function () {
            return $this->mockCreate;
        });
        $this->sm->setFactory(
            'AdminUserUpdate', function () {
            return $this->mockUpdate;
        });
        $this->sm->setFactory(
            'AuthenticationService', function () {
            return $this->mockService;
        });
        $this->sm->setFactory(
            'DataValidator', function () {
            return $this->mockDataValidator;
        });
    }

}
