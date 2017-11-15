<?php

namespace ActionLogTest\Model\DataContainer;

use ActionLog\Model\DataContainer\ActionLog;
use PHPUnit_Framework_TestCase;

class ActionLogTest extends PHPUnit_Framework_TestCase {

    public function testContainer() {
        $crit = [
            'id'            => 'id',
            'token_id'      => 'token_id',
            'route'         => 'route',
            'method'        => 'method',
            'data'          => ['data'],
            'response_code' => 201
        ];

        $actionLog = new ActionLog($crit);
        $this->assertEquals($crit['id'], $actionLog->getActionLogId());
        $this->assertEquals($crit['token_id'], $actionLog->getTokenId());
        $this->assertEquals($crit['route'], $actionLog->getRoute());
        $this->assertEquals($crit['method'], $actionLog->getMethod());
        $this->assertEquals(serialize($crit['data']), $actionLog->getData());
        $this->assertEquals($crit, $actionLog->getArrayCopy());
    }

}
