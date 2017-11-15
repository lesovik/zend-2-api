<?php

/*
 * Zend 2 API
 */

namespace Authentication\Model\Exception\Authentication;

use Zend\Http\Response;
use Authentication\Model\Exception\Authentication;

/**
 * 
 *  Invalid Username exception
 *
 * @author Dmitry Lesov
 */
class InvalidUsername extends Authentication {

    const MESSAGE = "Supplied username is not found";

    public function __construct() {
        parent::__construct( self::MESSAGE, Response::STATUS_CODE_404 );
    }

}
