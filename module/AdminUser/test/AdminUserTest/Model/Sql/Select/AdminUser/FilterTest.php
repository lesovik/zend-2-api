<?php

/*
 * Zend 2 API
 */

namespace AdminUserTest\Model\Sql\Select\AdminUser;

use Zend\Db\Sql\Select as ZendSelect;
use AdminUser\Model\Sql\Select\AdminUser\Filter as Select;
use AdminUser\Model\DataContainer\AdminUser as Criteria;
use AdminUserTest\AdminUserBootstrap;
use PHPUnit_Framework_TestCase;

/**
 * 
 *
 * @author Dmitry Lesov
 */
class FilterTest extends PHPUnit_Framework_TestCase {

    public function dataCriteria() {
        return [
            [
                new Criteria(['id' => 1])
            ],
            [
                new Criteria([
                    'login_status' => 'active'
                    ])
            ],
          
            [
                new Criteria([
                    'first_name' => 'test'
                    ])
            ],
            [
                new Criteria([
                    'last_name' => 'test'
                    ])
            ],
            [
                new Criteria([
                    'email' => 'test'
                    ])
            ],
            [
                new Criteria([
                    'user_role' => 'test'
                    ])
            ],
            [
                new Criteria([
                    'company_role' => 'test'
                    ])
            ],
        ];
    }

    /**
     * @dataProvider dataCriteria
     * @param Criteria $criteria
     */
    public function testConstruct( Criteria $criteria ) {
        $sm = AdminUserBootstrap::getServiceManager();


        $sql     = new ZendSelect();
        $adapter = $sm->get('dbAdminAdapter');

        $sql->from(['login' => 'login'])
            ->columns([
                 'id',
                'last_modified',
                'login_status',
                'user_role',
                'created',
                'username',
                'first_name',
                'last_name',
                'email',
            ])
          
        ;

        if ( $loginId = $criteria->getLoginId() ) {
            $sql->where(['login.id' => $loginId]);
        }
        if ( $loginStatus = $criteria->getLoginStatus() ) {
            $sql->where(['login.login_status' => $loginStatus]);
        }
        
        if ( $lastName = $criteria->getLastName() ) {
            $sql->where->expression(
                'upper(login.last_name) like ?', "%" . strtoupper($lastName) . "%");
        }
        if ( $firstName = $criteria->getFirstName() ) {
            $sql->where->expression(
                'upper(login.first_name) like ?', "%" . strtoupper($firstName) . "%");
        }
        if ( $email = $criteria->getEmail() ) {
            $sql->where->expression(
                'upper(login.email) like ?', "%" . strtoupper($email) . "%");
        }
        if ( $AdminUserRole = $criteria->getUserRole() ) {
            $sql->where(['login.user_role' => $AdminUserRole]);
        }
        
        $select = new Select($criteria);
        $this->assertEquals(
            $sql->getSqlString($adapter->getPlatform()), $select->getSqlString($adapter->getPlatform())
        );
    }

}
