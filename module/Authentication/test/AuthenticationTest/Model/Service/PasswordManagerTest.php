<?php

namespace AuthenticationTest\Model\Service;

use Authentication\Model\Service\PasswordManager;
use PHPUnit_Framework_TestCase;

/**
 * Json View generator
 *
 * @author Dmitry Lesov
 */
class PassworManagerTest extends PHPUnit_Framework_TestCase {

    
    public function testValidate() {

        $pm = new PasswordManager();

    
        $this->assertEquals(
            true, $pm->validate($pm->create_hash('test'),'test')
        );
    }

    
}
