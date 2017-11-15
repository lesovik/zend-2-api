<?php

/*
 * Zend 2 API
 */

namespace Authentication\Model\Sql\Insert;

use Zend\Db\Sql\Insert;
use Authentication\Model\DataContainer\AccessToken as DataContainer;

/**
 * 
 *  AccessToken insert query
 *
 * @author Dmitry Lesov
 */
class AccessToken extends Insert {

    /**
     * 
     *  AccessToken insert query
     *
     * @param DataContainer $token
     * @return void
     */
    public function __construct( DataContainer $token ) {
        parent::__construct();
        $this
            ->into('access_token')
            ->values([
                'login_id' => $token->getLoginId(),
                'token' => $token->getToken(),
                'ip' => $token->getIp(),
                'expires' => $token->getExpiryDate(),
        ]);
    }

}
