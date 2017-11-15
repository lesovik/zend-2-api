<?php

namespace ActionLog;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use ActionLog\Model\Service\ActionLog;
use ActionLog\Controller\ActionLogController;
use Zend\View\Model\JsonModel;
use Zend\ServiceManager\ServiceManager;
use Zend\Http\Request;

class Module {

    public function onBootstrap( MvcEvent $event ) {
        $eventManager        = $event->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(MvcEvent::EVENT_FINISH, $this->logAction(), -100);
    }

    /**
     * Intercepts action and logs it
     * @return callable
     */
    private function logAction() {
        return function(MvcEvent $event) {
            $logger = $event
                ->getApplication()
                ->getServiceManager()
                ->get('ActionLogCreate');
            $logger->log($event);
        };
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
                'ActionLogGet' => function (ServiceManager $sm) {
                    return new ActionLog\Get($sm);
                },
                'ActionLogCreate' => function (ServiceManager $sm) {
                    return new ActionLog\Create($sm);
                },
                'ActionLogGetList' => function (ServiceManager $sm) {
                    return new ActionLog\GetList($sm);
                },
            ],
        ];
    }

    public function getControllerConfig() {
        return array(
            'factories' => array(
                'ActionLog\Controller\ActionLog' => function () {
                    return new ActionLogController(
                        new JsonModel()
                    );
                },
            ),
        );
    }

}
