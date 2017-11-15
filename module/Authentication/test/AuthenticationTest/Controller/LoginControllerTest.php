<?php

namespace AuthenticationTest\Controller;

use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Http\PhpEnvironment\Response;
use Zend\Json\Json;
use Core\Test\PHPUnit\Controller\ControllerTestCase;
use AuthenticationTest\AuthenticationBootstrap as AuthenticationBootstrap;
use Authentication\Controller\LoginController;
use Authentication\Model\View\Authentication as AuthenticationJson;
use Authentication\Model\View\Authentication\PageNotFound as PageNotFoundJson;
use Authentication\Model\Exception\PageNotFound as PageNotFoundException;

class LoginControllerTest extends ControllerTestCase {

    /** @var ServiceManager */
    protected $sm;

    /** @var Request */
    protected $request;

    /** @var RouteMatch */
    protected $routeMatch;

    /** @var MvcEvent */
    protected $event;
    protected $mockService;
    protected $mockAdapter;
    protected $mockStorage;
    protected $mockToken;

    protected function setUp() {

        $this->sm         = AuthenticationBootstrap::getServiceManager();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(['controller' => 'login']);
        $this->event      = new MvcEvent();
        $this->event->setApplication(AuthenticationBootstrap::getApplication());
        $this->setRequestTypeToHttp($this->sm);
        $this->setApplicationConfig($this->sm->get('ApplicationConfig'));
    }

    private function setUpController( LoginController $controller ) {
        $config       = $this->sm->get('Config');
        $routerConfig = $config['router'] ? : [];
        $router       = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);

        $controller->setEvent($this->event);
        $controller->setServiceLocator($this->sm);
    }

    private function setMocks() {
        $this->mockService = $this->getMockBuilder('Zend\Authentication\AuthenticationService')
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter', 'getStorage', 'authenticate'])
            ->getMock();

        $this->mockAdapter = $this->getMockBuilder('Authentication\Model\Service\AuthenticationAdapter')
            ->disableOriginalConstructor()
            ->setMethods(['setUp', 'getLoginId'])
            ->getMock();

        $this->mockStorage = $this->getMockBuilder('Authentication\Model\Service\TokenStorage')
            ->disableOriginalConstructor()
            ->setMethods(['setIdentity', 'getToken'])
            ->getMock();

        $this->mockToken = $this->getMockBuilder('Authentication\Model\DataContainer\AccessToken')
            ->disableOriginalConstructor()
            ->setMethods(['getToken'])
            ->getMock();
        $this->sm->setAllowOverride(true);
    }

    private function setSuccessExpectations( $post ) {
        $this->mockAdapter->expects($this->at(1))
            ->method('getLoginId');
        $this->mockStorage->expects($this->at(1))
            ->method('getToken')
            ->will($this->returnValue($this->mockToken));
        $this->mockToken
            ->method('getToken')
            ->will($this->returnValue($post['token']));
    }

    private function setFailureExpectations( $postType ) {
        $exceptionName = 'Authentication\Model\Exception\Authentication\\' . $postType;
        $this->mockService->expects($this->at(2))
            ->method('authenticate')
            ->will($this->throwException(new $exceptionName()));
    }

    private function setExpectations( $post, $postType ) {
        $this->setMocks();
        $this->mockService->expects($this->at(0))
            ->method('getAdapter')
            ->will($this->returnValue($this->mockAdapter));
        $this->mockService->expects($this->at(1))
            ->method('getStorage')
            ->will($this->returnValue($this->mockStorage));
        if ( $postType == 'success' ) {
            $this->setSuccessExpectations($post, $postType);
        } else {
            $this->setFailureExpectations($postType);
        }

        $this->sm->setFactory(
            'AuthenticationService', function () {
            return $this->mockService;
        });
    }

    public function dataLoginPosts() {
        return [
            [
                [
                    'username' => 'asdsadas',
                    'password' => 'asdsadas'
                ],
                'InvalidUsername'
            ],
            [
                [
                    'username' => 'testuser',
                    'password' => 'asdsadas'
                ],
                'InvalidPassword'
            ],
            [
                [
                    'usernamed'   => 'testuser',
                    'passwordasd' => 'asdsadas'
                ],
                'MissingParameters'
            ],
            [
                [
                    'token' => 'Expected Token',
                ],
                'success'
            ],
        ];
    }

    /**
     * @dataProvider dataLoginPosts
     * @param Array $post
     * @param String $postType
     */
    public function testLoginAction( $post, $postType ) {

        $this->setExpectations($post, $postType);

        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent(Json::encode($post));

        $controller = new LoginController(
            $this->sm->get('AuthenticationService'), new AuthenticationJson()
        );
        $this->setUpController($controller);

        $result   = $controller->dispatch($this->request);
        $response = $controller->getResponse();

        $this->assertInstanceOf(
            'Authentication\Model\View\Authentication', $result
        );

        if ( $postType == 'success' ) {
            $this->assertSuccess($result, $response, $post);
        } else {
            $this->assertFailure($result, $response, $postType);
        }
    }

    private function assertFailure( $result, $response, $postType ) {
        $exceptionName     = 'Authentication\Model\Exception\Authentication\\' . $postType;
        $expectedException = new $exceptionName();

        $this->assertEquals(
            $expectedException->getCode(), $response->getStatusCode()
        );
        $this->assertEquals(
            $expectedException->getMessage(), $result->getVariable('message')
        );
    }

    private function assertSuccess( $result, $response, $post ) {
        $this->assertEquals(
            Response::STATUS_CODE_200, $response->getStatusCode()
        );
        $this->assertEquals(
            $post['token'], $result->getVariable('accessToken')
        );
    }

}
