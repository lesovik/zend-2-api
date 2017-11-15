<?php

namespace ActionLogTest\Model\Service;

use ActionLog\Model\Service\ActionLog;
use PHPUnit_Framework_TestCase;
use ActionLogTest\ActionLogBootstrap;

class ActionLogTest extends PHPUnit_Framework_TestCase {

    public function testActionLog() {
        $sm        = ActionLogBootstrap::getServiceManager();
        $ActionLog = new ActionLogTestDummy($sm);
        $this->assertAttributeInstanceOf('Core\Db\SqlExecuter', 'executer', $ActionLog);
        $this->assertAttributeInstanceOf('Core\Form\DataValidator', 'validator', $ActionLog);
        $this->assertAttributeInstanceOf('Zend\ServiceManager\ServiceManager', 'serviceManager', $ActionLog);
    }

}

class ActionLogTestDummy extends ActionLog {

    public function execute( array $parameters ) {
        
    }

}
