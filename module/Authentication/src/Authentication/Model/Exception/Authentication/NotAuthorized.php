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
class NotAuthorized extends Authentication {

    const MESSAGE = "You are not authorized for this resource";

    public function __construct() {
        parent::__construct( self::MESSAGE, Response::STATUS_CODE_403 );
    }

}
