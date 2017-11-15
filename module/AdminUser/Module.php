<?php

namespace AdminUser;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use AdminUser\Model\Service\AdminUser;
use AdminUser\Controller\AdminUserController;
use Zend\View\Model\JsonModel;
use Zend\ServiceManager\ServiceManager;

class Module {

    public function onBootstrap( MvcEvent $event ) {
        $eventManager        = $event->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

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
            'factories' => [
                'AdminUserGet' => function (ServiceManager $sm) {
                    return new AdminUser\Get($sm);
                },
                'AdminUserGetList' => function (ServiceManager $sm) {
                    return new AdminUser\GetList($sm);
                },
                'AdminUserCreate' => function (ServiceManager $sm) {
                    return new AdminUser\Create($sm);
                },
                'AdminUserUpdate' => function (ServiceManager $sm) {
                    return new AdminUser\Update($sm);
                },
            ],
        ];
    }

      public function getControllerConfig() {
        return array(
            'factories'                                => array(
                'AdminUser\Controller\AdminUser' => function () {
                    return new AdminUserController(
                        new JsonModel()
                    );
                },
            ),
        );
    }

}
