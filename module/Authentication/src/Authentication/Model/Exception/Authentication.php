<?php

/*
 * Zend 2 API
 */

namespace Authentication\Model\Exception;

use Core\Exception;

/**
 * 
 *  Authentication exception
 *
 * @author Dmitry Lesov
 */
class Authentication extends Exception {

    const MESSAGE = "Authentication Failed : ";

    public function __construct( $message , $code = 0 ) {
        parent::__construct( self::MESSAGE.$message,$code );
    }

}
