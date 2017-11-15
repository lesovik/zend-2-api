<?php

/*
 * Zend 2 API
 */

namespace Authentication\Model\Exception\Authentication;

use Zend\Http\Response;
use Authentication\Model\Exception\Authentication;

/**
 * 
 *  Invalid Token exception
 *
 * @author Dmitry Lesov
 */
class InvalidToken extends Authentication {

    const MESSAGE = "Supplied token is invalid";

    public function __construct() {
        parent::__construct( self::MESSAGE, Response::STATUS_CODE_401 );
    }

}
