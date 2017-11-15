<?php

namespace ActionLogTest\Controller;

use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Core\Test\PHPUnit\Controller\ControllerTestCase;
use ActionLogTest\ActionLogBootstrap;
use ActionLog\Controller\ActionLogController;
use Zend\View\Model\JsonModel;
use PHPUnit_Framework_MockObject_MockObject;

class ActionLogControllerTest extends ControllerTestCase {

    /** @var ServiceManager */
    protected $sm;

    /** @var Request */
    protected $request;

    /** @var RouteMatch */
    protected $routeMatch;

    /** @var MvcEvent */
    protected $event;

    /** @var  ActionLogController */
    protected $controller ;
    /** @var  PHPUnit_Framework_MockObject_MockObject */
    protected $mockGet;
    protected $mockGetList;
    protected $mockSendTest;
    protected $validator;

    protected function setUp() {

        $this->sm         = ActionLogBootstrap::getServiceManager();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(['controller' => 'action-log']);
        $this->event      = new MvcEvent();
        $this->event->setApplication(ActionLogBootstrap::getApplication());
        $this->setRequestTypeToHttp($this->sm);
        $this->setApplicationConfig($this->sm->get('ApplicationConfig'));
        $this->controller = new ActionLogController(new JsonModel());
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

        $this->routeMatch->setParam('actionLogId', '1');

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


        $result = $this->controller->dispatch($this->request);
        $this->assertInstanceOf(
            'Zend\View\Model\ViewModel', $result
        );
        foreach ($expected as $key => $val) {
            $results=$result->getVariable('results');
            $this->assertEquals(
                $val, $results[$key]
            );
        }
    }



    private function setUpController( ActionLogController $controller ) {
        $config       = $this->sm->get('Config');
        $routerConfig = $config['router'] ? : [];
        $router       = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);

        $controller->setEvent($this->event);
        $controller->setServiceLocator($this->sm);
    }

    private function setMocks() {

        $this->mockGet           = $this->getMockBuilder('ActionLog\Model\Service\ActionLog\Get')
            ->setConstructorArgs([$this->sm])
            ->setMethods(['execute', 'getData'])
            ->getMock();
        $this->mockDataValidator = $this->getMockBuilder('Core\Form\DataValidator')
            ->setMethods(['getData', 'getMessages'])
            ->getMock();
        $this->mockGetList       = $this->getMockBuilder('ActionLog\Model\Service\ActionLog\GetList')
            ->setConstructorArgs([$this->sm])
            ->setMethods(['execute', 'getData', 'getValidator'])
            ->getMock();
        ;
   
       
        $this->sm->setAllowOverride(true);
        $this->sm->setFactory(
            'ActionLogGet', function () {
            return $this->mockGet;
        });

        $this->sm->setFactory(
            'ActionLogGetList', function () {
            return $this->mockGetList;
        });      
       
        $this->sm->setFactory(
            'DataValidator', function () {
            return $this->mockDataValidator;
        });
    }

}
