<?php

/*
 * Zend 2 API
 */

namespace Authentication\Model\Exception\Authentication;

use Zend\Http\Response;
use Authentication\Model\Exception\Authentication;

/**
 * 
 *  Missing Parametersexception
 *
 * @author Dmitry Lesov
 */
class MissingParameters extends Authentication {

    const MESSAGE = "One of requied keys (username,password) is missing or empty in request JSON";

    public function __construct() {
        parent::__construct( self::MESSAGE, Response::STATUS_CODE_400 );
    }

}
