<?php

/*
 * Zend 2 API
 */

namespace ActionLogTest\Model\Sql\Insert;

use Zend\Db\Sql\Insert as ZendInsert;
use ActionLog\Model\Sql\Insert\ActionLog;
use ActionLog\Model\DataContainer\ActionLog as Criteria;
use ActionLogTest\ActionLogBootstrap;
use PHPUnit_Framework_TestCase;

/**
 * 
 *
 * @author Dmitry Lesov
 */
class ActionLogTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {

        $actionLog = new Criteria([
            'token_id' => '$this->actionLog->getTokenId()',
            'route'    => '$this->actionLog->getRoute()',
            'method'   => ' $this->actionLog->getMethod()',
            'data'     => '$this->actionLog->getData()',
            'response_code'     => '$this->actionLog->getData()',
        ]);
        $sm        = ActionLogBootstrap::getServiceManager();


        $adapter = $sm->get('dbAdminAdapter');
        
        $sql=new ZendInsert();
        $sql->into('action_log')
            ->values([
                'token_id' => $actionLog->getTokenId(),
                'route'    => $actionLog->getRoute(),
                'method'   => $actionLog->getMethod(),
                'data'   => $actionLog->getData(),
                'response_code'     => $actionLog->getResponseCode(),
        ]);

        $insert = new ActionLog($actionLog);
        $this->assertEquals(
            $sql->getSqlString($adapter->getPlatform()), $insert->getSqlString($adapter->getPlatform())
        );
    }

}
