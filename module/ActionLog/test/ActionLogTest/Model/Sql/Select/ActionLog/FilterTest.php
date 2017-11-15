<?php

/*
 * Zend 2 API
 */

namespace ActionLogTest\Model\Sql\Select\ActionLog;

use Zend\Db\Sql\Select as ZendSelect;
use ActionLog\Model\Sql\Select\ActionLog\Filter as Select;
use ActionLog\Model\DataContainer\ActionLog as Criteria;
use ActionLogTest\ActionLogBootstrap;
use PHPUnit_Framework_TestCase;

/**
 * 
 *
 * @author Dmitry Lesov
 */
class FilterTest extends PHPUnit_Framework_TestCase {

    public function dataCriteria() {
        return [

            [
                new Criteria([
                    'id' => 'active'
                    ])
            ],
            [
                new Criteria([
                    'token_id' => 'test'
                    ])
            ],
            [
                new Criteria([
                    'route' => 'test'
                    ])
            ],
            [
                new Criteria([
                    'method' => 'test'
                    ])
            ],
            [
                new Criteria([
                    'response_code' => 'test'
                    ])
            ],
        ];
    }

    /**
     * @dataProvider dataCriteria
     * @param Criteria $criteria
     */
    public function testConstruct( Criteria $criteria ) {
        $sm = ActionLogBootstrap::getServiceManager();


        $sql     = new ZendSelect();
        $adapter = $sm->get('dbAdminAdapter');

        $sql->from(['action_log' => 'action_log'])
            ->columns([
                'id',
                'token_id',
                'route',
                'method',
                'data',
                'response_code'
            ])

        ;

        if ( $id = $criteria->getActionLogId() ) {
            $sql->where(['action_log.id' => $id]);
        }
        if ( $tokenId = $criteria->getTokenId() ) {
            $sql->where(['action_log.token_id' => $tokenId]);
        }

        if ( $method = $criteria->getMethod() ) {
            $sql->where(['action_log.method' => $method]);
        }
        if ( $route = $criteria->getRoute() ) {
            $sql->where(['action_log.route' => $route]);
        }
        if ( $code = $criteria->getResponseCode() ) {
            $sql->where(['action_log.response_code' => $code]);
        }

        $select = new Select($criteria);
        $this->assertEquals(
            $sql->getSqlString($adapter->getPlatform()), $select->getSqlString($adapter->getPlatform())
        );
    }

}
