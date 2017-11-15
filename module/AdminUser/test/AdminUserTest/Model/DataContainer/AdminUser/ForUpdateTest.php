<?php

namespace AdminUserTest\Model\DataContainer;

use AdminUser\Model\DataContainer\AdminUser\ForUpdate;
use Core\Form\DataValidator;
use PHPUnit_Framework_TestCase;

class ForUpdateTest extends PHPUnit_Framework_TestCase {

    public function testContainer() {
        $crit = [
            'id'              => 'dfs',
            'user_role'       => 'AdminUserRole test',
            'email'           => 'email test',
            'first_name'      => 'firstName test',
            'last_name'       => 'lastName test',
            'password'        => 'lastName test',
            'username'        => 'lastName test',
            'login_status'        => 'lastName test'
        ];
        $AdminUser = new ForUpdate($crit);

        $this->assertEquals($crit, $AdminUser->getArrayCopy());
        $this->mockAdapter = $this->getMockBuilder('Zend\Db\Adapter\Adapter')
            ->disableOriginalConstructor()
            ->setMethods(['getDriver'])
            ->getMock();
        $dataValidator     = new DataValidator();
        $dataValidator->setUp($AdminUser, $this->mockAdapter);
        //id
        $this->assertTrue($dataValidator->has('id'));
        if ( $dataValidator->has('id') ) {
            $filter          = $dataValidator->getInputFilter()
                ->get('id');
            $validators = $filter
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\Digits', $validators[0]['instance']);
            $this->assertTrue($validators[0]['breakChainOnFailure']);
            $this->assertInstanceOf('Zend\Validator\Db\RecordExists', $validators[1]['instance']);
            $this->assertEquals('login', $validators[1]['instance']->getTable());
            $this->assertEquals('id', $validators[1]['instance']->getField());
            $this->assertTrue($filter->isRequired());
        }        

        //AdminUser_role
        $this->assertTrue($dataValidator->has('user_role'));
        if ( $dataValidator->has('user_role') ) {
            $filter = $dataValidator->getInputFilter()
                ->get('user_role');

            $this->assertFalse($filter->isRequired());
            $this->assertFalse($filter->allowEmpty());
            $validators = $filter
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\InArray', $validators[0]['instance']);
            $roles              = [
                'ROOT',
                'ADMIN',
                'MANAGER',
                'INTERN'
            ];
            $this->assertEquals($roles, $validators[0]['instance']->getHaystack());
        }
        
        
        //login status
        $this->assertTrue($dataValidator->has('login_status'));
        if ( $dataValidator->has('login_status') ) {
            $loginStatus = $dataValidator->getInputFilter()
                ->get('login_status');

            $this->assertFalse($loginStatus->isRequired());
            $this->assertFalse($loginStatus->allowEmpty());
            $loginStatusValidators = $loginStatus
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\InArray', $loginStatusValidators[0]['instance']);
            $stati                 = [
                'ACTIVE',
                'SUSPENDED',
                'DELETED',
            ];
            $this->assertEquals($stati, $loginStatusValidators[0]['instance']->getHaystack());
        }
        
        $this->assertTrue($dataValidator->has('email'));
        
        //email
        if ( $dataValidator->has('email') ) {
            $email = $dataValidator->getInputFilter()
                ->get('email');

            $this->assertFalse($email->isRequired());
            $this->assertFalse($email->allowEmpty());
            $emailValidators = $email
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\EmailAddress', $emailValidators[0]['instance']);
            $this->assertInstanceOf('Zend\Validator\Db\NoRecordExists', $emailValidators[1]['instance']);
            $this->assertEquals('login', $emailValidators[1]['instance']->getTable());
            $this->assertEquals('email', $emailValidators[1]['instance']->getField());
        }
        $this->assertTrue($dataValidator->has('username'));
        if ( $dataValidator->has('username') ) {
            $uName           = $dataValidator->getInputFilter()
                ->get('username');
            $this->assertFalse($uName->isRequired());
            $this->assertFalse($uName->allowEmpty());
            $uNameValidators = $uName
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\I18n\Validator\Alnum', $uNameValidators[0]['instance']);
            $this->assertInstanceOf('Zend\Validator\Db\NoRecordExists', $uNameValidators[1]['instance']);
            $this->assertEquals('login', $uNameValidators[1]['instance']->getTable());
            $this->assertEquals('username', $uNameValidators[1]['instance']->getField());
        }
        $this->assertTrue($dataValidator->has('password'));
        if ( $dataValidator->has('password') ) {
            $password          = $dataValidator->getInputFilter()
                ->get('password');
            $this->assertFalse($password->isRequired());
            $this->assertFalse($password->allowEmpty());
            $passwordValidators = $password
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\Regex', $passwordValidators[0]['instance']);
             $this->assertEquals('/^(?=.*[^a-zA-Z])(?=.*[a-z])(?=.*[A-Z])\S{8,}/', $passwordValidators[0]['instance']->getPattern());
        }
       
        $this->assertTrue($dataValidator->has('first_name'));
        if ( $dataValidator->has('first_name') ) {
            $fName           = $dataValidator->getInputFilter()
                ->get('first_name');
            $this->assertFalse($fName->isRequired());
            $fNameValidators = $fName
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\StringLength', $fNameValidators[0]['instance']);
            $this->assertEquals(2, $fNameValidators[0]['instance']->getOption('min'));
            $this->assertInstanceOf('Zend\I18n\Validator\Alnum', $fNameValidators[1]['instance']);
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
            $this->assertInstanceOf('Zend\I18n\Validator\Alnum', $lNameValidators[1]['instance']);
            $this->assertFalse($lName->isRequired());
            $this->assertFalse($lName->allowEmpty());
        }
    }

}
