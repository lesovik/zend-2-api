<?php

namespace Authentication\Model\View\Authentication;

use Authentication\Model\View\Authentication;
use Authentication\Model\Exception\Authentication\InvalidToken as Exception;
use Zend\Mvc\MvcEvent;


/**
 * FailedAuthentication Json View generator
 *
 * @author Dmitry Lesov
 */
class FailedAuthentication extends Authentication {

    public function __construct( MvcEvent $e ) {
        parent::__construct();
        parent::setFail(new Exception(), $e);
    }

}
