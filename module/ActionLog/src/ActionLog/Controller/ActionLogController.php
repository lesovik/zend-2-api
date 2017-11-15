<?php

namespace ActionLog\Controller;

use Core\Controller\RestfulJsonController;
use Zend\View\Model\JsonModel;
use ActionLog\Model\Service\ActionLog as Service;
use Zend\Http\Response;

class ActionLogController extends RestfulJsonController {

    protected $view;

    public function __construct( JsonModel $view ) {

        $this->setIdentifierName('actionLogId');
        $this->view = $view;
    }

    public function get( $id ) {
        $service    = $this->getServiceLocator()->get('ActionLogGet');
        $parameters = ['id' => $id];

        if ( $service->execute($parameters) ) {
            $this->view->setVariables($service->getData());
        } else {
            $this->setErrors($service, Response::STATUS_CODE_404);
        }

        return $this->view;
    }

    public function getList() {
        $service = $this->getServiceLocator()->get('ActionLogGetList');

        if ( $service->execute($this->params()->fromQuery()) ) {
            $data = $service->getData();
            if ( !count($data) ) {
                $this->getEvent()->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            }
            $this->view->setVariables(array_merge(
                    ['results' => $data], $service->getCriteriaArray()
                )
            );
        } else {
            $this->setErrors($service);
        }

        return $this->view;
    }
    protected function setErrors( Service $service, $statusCode = Response::STATUS_CODE_400 ) {
        $this->getEvent()->getResponse()->setStatusCode($statusCode);
        $this->view->setVariables([
            'data'   => $service->getValidator()->getData(),
            'errors' => $service->getValidator()->getMessages(),
        ]);
    }

  
}
