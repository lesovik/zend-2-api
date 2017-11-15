<?php

namespace AdminUserTest\Model\DataContainer;

use AdminUser\Model\DataContainer\AdminUser\ForSelect;
use Core\Form\DataValidator;
use PHPUnit_Framework_TestCase;

class ForSelectTest extends PHPUnit_Framework_TestCase {

    public function testContainer() {
        $crit = [
            'user_role'    => 'AdminUserRole test',
            'login_status' => 'email test',
            'first_name'   => 'firstName test',
            'last_name'    => 'lastName test',
            'email'        => 'lastName test',
            'sort'         => 'lastName test',
            'order'        => 'lastName test',
            'skip'         => 'lastName test',
            'limit'        => 'lastName test',
        ];
        $AdminUser = new ForSelect($crit);

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

            $this->assertFalse($AdminUserRole->isRequired());
            $AdminUserRoleValidators = $AdminUserRole
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\InArray', $AdminUserRoleValidators[0]['instance']);
            $roles              = [
                'ADMIN',
                'MANAGER',
                'ROOT',
                'INTERN'
            ];
            $this->assertEquals($roles, $AdminUserRoleValidators[0]['instance']->getHaystack());
        }
      
        $this->assertTrue($dataValidator->has('login_status'));
        if ( $dataValidator->has('login_status') ) {
            $loginStatus = $dataValidator->getInputFilter()
                ->get('login_status');

            $this->assertFalse($loginStatus->isRequired());
            $loginStatusValidators = $loginStatus
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\InArray', $loginStatusValidators[0]['instance']);
            $stati                 = [
                'ACTIVE',
                'SUSPENDED'
            ];
            $this->assertEquals($stati, $loginStatusValidators[0]['instance']->getHaystack());
        }

        $this->assertTrue($dataValidator->has('email'));
        if ( $dataValidator->has('email') ) {
            $email = $dataValidator->getInputFilter()
                ->get('email');

            $this->assertFalse($email->isRequired());
            $emailValidators = $email
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\StringLength', $emailValidators[0]['instance']);
            $this->assertEquals(5, $emailValidators[0]['instance']->getOption('min'));
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
        }
      
        $this->assertTrue($dataValidator->has('sort'));
        if ( $dataValidator->has('sort') ) {
            $sort = $dataValidator->getInputFilter()
                ->get('sort');

            $this->assertFalse($sort->isRequired());
            $sortValidators = $sort
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\InArray', $sortValidators[0]['instance']);
            $opt          = [
                'id',
                'first_name',
                'last_name'
            ];
            $this->assertEquals($opt, $sortValidators[0]['instance']->getHaystack());
        }
        $this->assertTrue($dataValidator->has('order'));
        if ( $dataValidator->has('order') ) {
            $sort = $dataValidator->getInputFilter()
                ->get('order');

            $this->assertFalse($sort->isRequired());
            $sortValidators = $sort
                ->getValidatorChain()
                ->getValidators();
            $this->assertInstanceOf('Zend\Validator\InArray', $sortValidators[0]['instance']);
            $opt          = [
                'ASC',
                'DESC',
            ];
            $this->assertEquals($opt, $sortValidators[0]['instance']->getHaystack());
        }
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
