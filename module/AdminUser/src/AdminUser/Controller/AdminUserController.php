<?php

namespace AdminUser\Controller;

use Core\Controller\RestfulJsonController;
use Zend\View\Model\JsonModel;
use AdminUser\Model\Service\AdminUser as Service;
use Zend\Http\Response;
use Zend\Http\Request;

class AdminUserController extends RestfulJsonController {

    protected $view;

    public function __construct( JsonModel $view ) {

        $this->view = $view;
    }

    public function get( $id ) {
        $service    = $this->getServiceLocator()->get('AdminUserGet');
        $parameters = ['id' => $id];

        if ( $service->execute($parameters) ) {
            $this->view->setVariables($service->getData());
        } else {
            $this->setErrors($service, Response::STATUS_CODE_404);
        }

        return $this->view;
    }

    public function getList() {
        $service = $this->getServiceLocator()->get('AdminUserGetList');

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

    public function update( $id, $data ) {
        $service = $this->getServiceLocator()->get('AdminUserUpdate');


        $service->setId($id);
        if ( $service->execute($data) ) {
            $this->view->setVariables($service->getData());
        } else {
            $this->setErrors($service);
        }

        return $this->view;
    }

    public function delete( $id ) {
        $service = $this->getServiceLocator()->get('AdminUserUpdate');
        $service->setId($id);
        if ( $service->execute(['login_status' => 'DELETED']) ) {
            $this->view->setVariables($service->getData());
        } else {
            $this->setErrors($service);
        }
        return $this->view;
    }

    public function replaceList( $data ) {

        $service = $this->getServiceLocator()->get('AdminUserUpdate');
        if ( $service->execute($data) ) {
            $this->view->setVariables($service->getData());
        } else {
            $this->setErrors($service);
        }
        return $this->view;
    }

    public function create( $data ) {
        $service = $this->getServiceLocator()->get('AdminUserCreate');
        if ( $service->execute($data) ) {
            $this->view->setVariables($service->getData());
        } else {
            $this->setErrors($service);
        }
        return $this->view;
    }

    public function options() {
        return $this->view;
    }

    public function meAction() {
        if ( $this->getRequest()->getMethod() == Request::METHOD_OPTIONS ) {
            return $this->view;
        }
        
        $id    = $this->getServiceLocator()
            ->get('AuthenticationService')
            ->getStorage()
            ->getToken()
            ->getLoginId();
        $service = $this->getServiceLocator()->get('AdminUserGet');
        if ( $service->execute(['id' => $id]) ) {
            $this->view->setVariables($service->getData());
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
