<?php

namespace ActionLogTest\Model\DataContainer;

use ActionLog\Model\DataContainer\ActionLog\ForSelect;
use Core\Form\DataValidator;
use PHPUnit_Framework_TestCase;

class ForSelectTest extends PHPUnit_Framework_TestCase {

    public function testContainer() {
        $crit = [
            'skip'    => 'ActionLogRole test',
            'limit' => 'email test',
            'token_id' => 'email test',
            'route' => 'email test',
            'method' => 'email test',
            'response_code' => null,
        ];
        $template = new ForSelect($crit);

        $this->assertEquals($crit, $template->getArrayCopy());
        $this->mockAdapter = $this->getMockBuilder('Zend\Db\Adapter\Adapter')
            ->disableOriginalConstructor()
            ->setMethods(['getDriver'])
            ->getMock();
        $dataValidator     = new DataValidator();
        $dataValidator->setUp($template, $this->mockAdapter);

        $this->assertTrue($dataValidator->has('skip'));
        if ( $dataValidator->has('skip') ) {
            $sort = $dataValidator->getInputFilter()
                ->get('skip');

            $this->assertFalse($sort->isRequired());
            $sortValidators = $sort
                ->getValidatorChain()
                ->getValidators();
           
            $this->assertInstanceOf('Zend\Validator\Digits', $sortValidators[0]['instance']);
        }
        $this->assertTrue($dataValidator->has('limit'));
        if ( $dataValidator->has('limit') ) {
            $sort = $dataValidator->getInputFilter()
                ->get('limit');

            $this->assertFalse($sort->isRequired());
            $sortValidators = $sort
                ->getValidatorChain()
                ->getValidators();
           
            $this->assertInstanceOf('Zend\Validator\Digits', $sortValidators[0]['instance']);
        }
    }

}
