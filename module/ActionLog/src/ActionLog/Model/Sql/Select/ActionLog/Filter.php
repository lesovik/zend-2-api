<?php

/*
 * Zend 2 API
 */

namespace ActionLog\Model\Sql\Select\ActionLog;

use ActionLog\Model\Sql\Select\ActionLog as Select;
use ActionLog\Model\DataContainer\ActionLog as Criteria;
use ActionLog\Model\DataContainer\ActionLog\ForSelect;

/**
 * 
 *  ActionLog select query filter
 *
 * @author Dmitry Lesov
 */
class Filter extends Select {

    /**
     * injects criteria container and sets up where statements
     * 
     * @dataProvider dataCriteria
     * @param Criteria $criteria
     */
    public function __construct( Criteria $criteria ) {
        parent::__construct();
        $arr = $criteria->getArrayCopy();
        if ( $id = $criteria->getActionLogId() ) {
            $this->where(['action_log.id' => $id]);
        }
        if ( $tokenId = $criteria->getTokenId() ) {
            $this->where(['action_log.token_id' => $tokenId]);
        }

        if ( $method = $criteria->getMethod() ) {
             $this->where(['action_log.method' => $method]);
        }
        if ( $route = $criteria->getRoute() ) {
             $this->where(['action_log.route' => $route]);
        }
        if ( $code = $criteria->getResponseCode() ) {
             $this->where(['action_log.response_code' => $code]);
        }

        if ( $criteria instanceof ForSelect ) {
            if ( $sort = $criteria->getSort() ) {
                $this->order([$sort => $criteria->getOrder()]);
            }
        }
    }

}
