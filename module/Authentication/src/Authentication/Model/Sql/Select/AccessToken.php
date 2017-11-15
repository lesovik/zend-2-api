<?php

/*
 * Zend 2 API
 */

namespace Authentication\Model\Sql\Select;

use Zend\Db\Sql\Select;
use Authentication\Model\DataContainer\Criteria\AccessToken as Criteria;

/**
 * 
 *  AccessToken select query
 *
 * @author Dmitry Lesov
 */
class AccessToken extends Select {

    /**
     * 
     *  Login select query
     *
     * @param  Criteria $criteria
     * @return void
     */
    public function __construct( Criteria $criteria ) {
        parent::__construct();
        $this
            ->from('access_token')
            ->columns([
                'id',
                'login_id',
                'token',
                'ip',
                'expires',
        ])
            ->join('login','access_token.login_id=login.id',[
                'user_role',
                'first_name',
                'last_name'
            ]);
        if ($criteria->getToken()) {
            $this->where([
                'token' => $criteria->getToken()
            ]);
        }
    }

}
