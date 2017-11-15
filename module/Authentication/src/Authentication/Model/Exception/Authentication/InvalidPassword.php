<?php

/*
 * Zend 2 API
 */

namespace Authentication\Model\Exception\Authentication;

use Zend\Http\Response;
use Authentication\Model\Exception\Authentication;

/**
 * 
 *  Invalid Password exception
 *
 * @author Dmitry Lesov
 */
class InvalidPassword extends Authentication {

    const MESSAGE = "Password does not match records";

    public function __construct() {
        parent::__construct( self::MESSAGE, Response::STATUS_CODE_401 );
    }

}
