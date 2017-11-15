<?php

namespace Authentication\Model\View\Authentication;

use Authentication\Model\View\Authentication;
use Authentication\Model\Exception\PageNotFound as Exception;
use Zend\Mvc\MvcEvent;


/**
 * PageNotFound Json View generator
 *
 * @author Dmitry Lesov
 */
class PageNotFound extends Authentication {

    public function __construct( MvcEvent $e ) {
        parent::__construct();
        parent::setFail(new Exception(), $e);
    }

}
