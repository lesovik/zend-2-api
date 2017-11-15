<?php

namespace AdminUserTest\Model\DataContainer;

use AdminUser\Model\DataContainer\AdminUser\ForInsert;
use Core\Form\DataValidator;
use PHPUnit_Framework_TestCase;

class ForInsertTest extends PHPUnit_Framework_TestCase {

    public function testContainer() {
        $crit = [
            'id'           => 'dfs',
            'user_role'    => 'AdminUserRole test',
            'email'        => 'email test',
            'first_name'   => 'firstName test',
            'last_name'    => 'lastName test',
        ];
        $AdminUser = new ForInsert($crit);

        $this->assertEquals($crit, $AdminUser->getArrayCopy());
        $this->mockAdapter = $this->getMockBuilder('Zend\Db\Adapter\Adapter')
            ->disableOriginalConstructor()
            ->setMethods(['getDriver'])
            ->getMock();
        $dataValidator     = new DataValidator();
        $dataValidator->setUp($AdminUser, $this->mockAdapter);
        $this->assertTrue($dataValidator->has('user_role'));
        if ( $dataValidator->has('user_role') ) {
            $AdminUserRole = $dataValidator->getInputFilter()
                ->get('user_role');

            $this->assertTrue($AdminUserRole->isRequired());
            $AdminUserRoleValidators = $AdminUserRole
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\InArray', $AdminUserRoleValidators[0]['instance']);
            $roles              = [
                'ROOT',
                'ADMIN',
                'MANAGER',
                'INTERN'
            ];
            $this->assertEquals($roles, $AdminUserRoleValidators[0]['instance']->getHaystack());
        }
        $this->assertTrue($dataValidator->has('email'));
        if ( $dataValidator->has('email') ) {
            $email = $dataValidator->getInputFilter()
                ->get('email');

            $this->assertTrue($email->isRequired());
            $emailValidators = $email
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\EmailAddress', $emailValidators[0]['instance']);
            $this->assertInstanceOf('Zend\Validator\Db\NoRecordExists', $emailValidators[1]['instance']);
            $this->assertEquals('login', $emailValidators[1]['instance']->getTable());
            $this->assertEquals('email', $emailValidators[1]['instance']->getField());
            
        }
        $this->assertTrue($dataValidator->has('first_name'));
        if ( $dataValidator->has('first_name') ) {
            $fName           = $dataValidator->getInputFilter()
                ->get('first_name');
            $this->assertTrue($fName->isRequired());
            $fNameValidators = $fName
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\StringLength', $fNameValidators[0]['instance']);
            $this->assertEquals(2, $fNameValidators[0]['instance']->getOption('min'));
        }
        $this->assertTrue($dataValidator->has('last_name'));
        if ( $dataValidator->has('last_name') ) {
            $lName           = $dataValidator->getInputFilter()
                ->get('last_name');
            $lNameValidators = $lName
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\StringLength', $lNameValidators[0]['instance']);
            $this->assertEquals(2, $lNameValidators[0]['instance']->getOption('min'));
            $this->assertTrue($lName->isRequired());
        }
      
    }

}
