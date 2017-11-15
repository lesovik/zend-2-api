<?php

namespace ActionLogTest\Model\DataContainer;

use ActionLog\Model\DataContainer\ActionLog\ForInsert;
use Core\Form\DataValidator;
use PHPUnit_Framework_TestCase;

class ForInsertTest extends PHPUnit_Framework_TestCase {

    public function testContainer() {
        $crit      = [
            'token_id'      => 'token_id',
            'route'         => 'route',
            'method'        => 'method',
            'data'          => 'data',
            'response_code' => 'daxzfdsfta'
        ];
        $ActionLog = new ForInsert($crit);

        $this->assertEquals($crit, $ActionLog->getArrayCopy());
        $this->mockAdapter = $this->getMockBuilder('Zend\Db\Adapter\Adapter')
            ->disableOriginalConstructor()
            ->setMethods(['getDriver'])
            ->getMock();
        $dataValidator     = new DataValidator();
        $dataValidator->setUp($ActionLog, $this->mockAdapter);

        $this->assertTrue($dataValidator->has('token_id'));
        if ( $dataValidator->has('token_id') ) {
            $email = $dataValidator->getInputFilter()
                ->get('token_id');

            $this->assertTrue($email->isRequired());
            $emailValidators = $email
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\Digits', $emailValidators[0]['instance']);
            $this->assertInstanceOf('Zend\Validator\Db\RecordExists', $emailValidators[1]['instance']);
            $this->assertEquals('access_token', $emailValidators[1]['instance']->getTable());
            $this->assertEquals('id', $emailValidators[1]['instance']->getField());
        }
        $this->assertTrue($dataValidator->has('route'));
        if ( $dataValidator->has('route') ) {
            $fName           = $dataValidator->getInputFilter()
                ->get('route');
            $this->assertTrue($fName->isRequired());
            $fNameValidators = $fName
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\StringLength', $fNameValidators[0]['instance']);
            $this->assertEquals(2, $fNameValidators[0]['instance']->getOption('min'));
        }
        $this->assertTrue($dataValidator->has('method'));
        if ( $dataValidator->has('method') ) {
            $lName           = $dataValidator->getInputFilter()
                ->get('method');
            $lNameValidators = $lName
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\StringLength', $lNameValidators[0]['instance']);
            $this->assertEquals(2, $lNameValidators[0]['instance']->getOption('min'));
            $this->assertTrue($lName->isRequired());
        }
    }

}
