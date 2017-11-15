<?php

namespace Authentication\Model\View;

use Zend\View\Model\JsonModel;
use Core\Exception;
use Authentication\Model\DataContainer\AccessToken;
use Zend\Mvc\MvcEvent;
use Zend\Http\Response as Response;

/**
 * Json View generator
 *
 * @author Dmitry Lesov
 */
class Authentication extends JsonModel {

    public function setFail( Exception $ex, MvcEvent $e) {
        $this->setVariable('message', $ex->getMessage());
        $e->getResponse()->setStatusCode(($ex->getCode())?:Response::STATUS_CODE_500);
        $e->setError($ex->getMessage());
    }

    public function setSuccess( AccessToken $token ) {
        $this->setVariable('accessToken', $token->getToken());
        $this->setVariable('accessTokenExp', strtotime($token->getExpiryDate()));
    }

}
