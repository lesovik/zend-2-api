<?php

/*
 * Zend 2 API
 */

namespace Authentication\Model\Exception;

use Zend\Http\Response;
use Core\Exception;

/**
 * 
 *  Error404
 *
 * @author Dmitry Lesov
 */
class PageNotFound extends Exception {

    const MESSAGE = "Could not resolve routing";

    public function __construct() {
        parent::__construct(self::MESSAGE, Response::STATUS_CODE_404);
    }

}
