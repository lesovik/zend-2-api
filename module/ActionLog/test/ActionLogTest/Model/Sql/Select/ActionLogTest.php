<?php

/*
 * Zend 2 API
 */

namespace ActionLogTest\Model\Sql\Select;

use Zend\Db\Sql\Select as ZendSelect;
use ActionLog\Model\Sql\Select\ActionLog as Select;
use ActionLogTest\ActionLogBootstrap;
use PHPUnit_Framework_TestCase;

/**
 * 
 *
 * @author Dmitry Lesov
 */
class ActionLogTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $sm = ActionLogBootstrap::getServiceManager();


        $sql     = new ZendSelect();
        $adapter = $sm->get('dbAdminAdapter');
        $sql
            ->from(['action_log' => 'action_log'])
            ->columns([
                'id',
                'token_id',
                'route',
                'method',
                'data',
                'response_code'
            ])

        ;
        $select = new Select();
        $this->assertEquals(
            $sql->getSqlString($adapter->getPlatform()), $select->getSqlString($adapter->getPlatform())
        );
    }

}
