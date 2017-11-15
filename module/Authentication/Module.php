<?php

namespace Authentication;

use Authentication\Model\Service\TokenStorage;
use Authentication\Model\Service\PasswordManager;
use Authentication\Model\Service\AuthenticationAdapter;
use Authentication\Controller\LoginController;
use Authentication\Model\View\Authentication as AuthenticationJson;
use Authentication\Model\View\Authentication\PageNotFound;
use Authentication\Model\View\Authentication\FailedAuthentication;
use Authentication\Model\View\Authentication\FailedAcl;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Uri\UriFactory;
use Zend\ServiceManager\ServiceManager;

class Module {

    /** @var  AuthenticationService */
    protected $authService;

    public function onBootstrap( MvcEvent $event ) {

        /**
         * Registering the chrome-extension custom scheme like this allows you to use Google Chrome extensions for testing your API.
         */
        UriFactory::registerScheme('chrome-extension', 'Zend\Uri\Uri');
        $this->initAcl($event);
        $eventManager = $event->getApplication()->getEventManager();


        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach(MvcEvent::EVENT_RENDER, $this->checkRoute(), 1);
        $eventManager->attach(MvcEvent::EVENT_ROUTE, $this->authenticate(), 1);
    }

    private function initAcl( MvcEvent $e ) {

        $acl          = new \Zend\Permissions\Acl\Acl();
        $roles        = include __DIR__ . '/config/module.acl.roles.php';
        $allResources = array();
        foreach ($roles as $role => $resources) {

            $role = new \Zend\Permissions\Acl\Role\GenericRole($role);
            $acl->addRole($role);

            $allResources = array_merge($resources, $allResources);

            foreach ($resources as $resource) {
                if ( !$acl->hasResource($resource) ) {
                    $acl->addResource(new \Zend\Permissions\Acl\Resource\GenericResource($resource));
                }
            }
            foreach ($allResources as $resource) {
                $acl->allow($role, $resource);
            }
        }
        $e->getViewModel()->acl = $acl;
    }

    private function checkAcl( MvcEvent $e ) {
        $route    = $e->getRouteMatch()->getMatchedRouteName();
        $token    = $this->authService->getStorage()->getToken();
        $userRole = ($token) ? $token->getUserRole() : 'guest';
        if ( !$e->getViewModel()->acl->hasResource($route) || !$e->getViewModel()->acl->isAllowed($userRole, $route) ) {
            $this->onFailedAcl($e);
        }
    }

    /**
     * Intercepts routing to perform authentication
     * @return callable
     */
    private function authenticate() {

        return function(MvcEvent $event) {
            $match = $event->getRouteMatch();
            $openRoutes=$event->getApplication()
                    ->getServiceManager()->get('Config')['openRoutes'];
  

            $this->authService = $event->getApplication()
                    ->getServiceManager()->get('AuthenticationService');

            if ( $event->getRequest()->getMethod() != Request::METHOD_OPTIONS ) {
                if (
                    !in_array($match->getMatchedRouteName(), $openRoutes) &&
                    !$this->authService->hasIdentity()
                ) {
                    $this->onFailedAuthentication($event);
                } else {
                    $this->checkAcl($event);
                }
            }
        };
    }

    /**
     * Intercepts rendering to fail with Json on 404
     * @return callable
     */
    private function checkRoute() {
        return function(MvcEvent $event) {

            $match = $event->getRouteMatch();
            if ( !$match instanceof RouteMatch ) {
                $this->onFailedRoute($event);
            }
        };
    }

    public function onFailedAcl( MvcEvent $e ) {
        $json = new FailedAcl($e);
        $e->setViewModel($json);
    }

    public function onFailedAuthentication( MvcEvent $e ) {
        $json = new FailedAuthentication($e);
        $e->setViewModel($json);
    }

    public function onFailedRoute( MvcEvent $e ) {
        $json = new PageNotFound($e);
        $e->setViewModel($json);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return [
            'factories'       => [
                'AuthenticationStorage' => function(ServiceManager $sm) {
                    return new TokenStorage(
                        $sm->get('request'), $sm->get('ExecuterAdmin')
                    );
                },
                'PasswordManager' => function() {
                    return new PasswordManager();
                },
                'AuthenticationService' => function($sm) {
                    return new AuthenticationService(
                        $sm->get('AuthenticationStorage'), $sm->get('AuthenticationAdapter')
                    );
                },
                'AuthenticationAdapter' => function($sm) {
                    return new AuthenticationAdapter(
                        $sm->get('dbAdminAdapter'), $sm->get('PasswordManager')
                    );
                },
            ],
        ];
    }

    public function getControllerConfig() {
        return array(
            'factories'                                => array(
                'Authentication\Controller\Login' => function ($sm) {
                    return new LoginController(
                        $sm->getServiceLocator()->get('AuthenticationService'), new AuthenticationJson()
                    );
                },
               
            ),
        );
    }

}
