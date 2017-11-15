<?php

namespace Authentication\Model\View\Authentication;

use Authentication\Model\View\Authentication;
use Authentication\Model\Exception\Authentication\NotAuthorized as Exception;
use Zend\Mvc\MvcEvent;


/**
 * NotAuthorized Json View generator
 *
 * @author Dmitry Lesov
 */
class FailedAcl extends Authentication {

    public function __construct( MvcEvent $e ) {
        parent::__construct();
        parent::setFail(new Exception(), $e);
    }

}
