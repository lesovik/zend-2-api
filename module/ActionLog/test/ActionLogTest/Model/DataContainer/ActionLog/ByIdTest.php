<?php

namespace ActionLogTest\Model\DataContainer;

use ActionLog\Model\DataContainer\ActionLog\ById;
use Core\Form\DataValidator;
use PHPUnit_Framework_TestCase;

class ByIdTest extends PHPUnit_Framework_TestCase {

    public function testContainer() {
        $crit = [
            'id'     => 'rtretret',
        ];
        $ActionLog = new ById($crit);

        $this->assertEquals($crit, $ActionLog->getArrayCopy());
        $this->mockAdapter = $this->getMockBuilder('Zend\Db\Adapter\Adapter')
            ->disableOriginalConstructor()
            ->setMethods(['getDriver'])
            ->getMock();
        $dataValidator     = new DataValidator();
        $dataValidator->setUp($ActionLog, $this->mockAdapter);
        
        $this->assertTrue($dataValidator->has('id'));
        if ( $dataValidator->has('id') ) {
            $email = $dataValidator->getInputFilter()
                ->get('id');

            $this->assertTrue($email->isRequired());
            $emailValidators = $email
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\Digits', $emailValidators[0]['instance']);
            $this->assertInstanceOf('Zend\Validator\Db\RecordExists', $emailValidators[1]['instance']);
            $this->assertEquals('action_log', $emailValidators[1]['instance']->getTable());
            $this->assertEquals('id', $emailValidators[1]['instance']->getField());
            
        }
    }

}
