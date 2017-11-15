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
class Uncategorized extends Authentication {

    public function __construct( $message = 'uncategorized' ) {
        parent::__construct($message, Response::STATUS_CODE_400);
    }

}
