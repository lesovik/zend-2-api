<?php

namespace AdminUserTest\Model\DataContainer\AdminUser;

use AdminUser\Model\DataContainer\AdminUser\ById;
use Core\Form\DataValidator;
use PHPUnit_Framework_TestCase;

class ByIdTest extends PHPUnit_Framework_TestCase {

    public function testContainer() {
        $crit              = [
            'id' => 'dfs',
        ];
        $AdminUser              = new ById($crit);
        $this->assertEquals($crit['id'], $AdminUser->getLoginId());
        $this->mockAdapter = $this->getMockBuilder('Zend\Db\Adapter\Adapter')
            ->disableOriginalConstructor()
            ->setMethods(['getDriver'])
            ->getMock();
        $dataValidator     = new DataValidator();
        $dataValidator->setUp($AdminUser, $this->mockAdapter);
        $this->assertTrue($dataValidator->has('id'));
        if ( $dataValidator->has('id') ) {
            $idValidators = $dataValidator->getInputFilter()
                ->get('id')
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\Digits', $idValidators[0]['instance']);
            $this->assertInstanceOf('Zend\Validator\Db\RecordExists', $idValidators[1]['instance']);
        }
    }

}
