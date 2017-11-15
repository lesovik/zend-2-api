<?php

namespace AuthenticationTest\Model\Service;

use Authentication\Model\Service\AuthenticationAdapter;
use Authentication\Model\Service\PasswordManager;
use Zend\Authentication\Result;
use Core\DataContainer;
use Zend\Db\Sql\Select;
use PHPUnit_Framework_TestCase;
use AuthenticationTest\AuthenticationBootstrap as AuthenticationBootstrap;

class AuthenticationAdapterTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $this->adapter = $this->getMockBuilder('Zend\Db\Adapter\Adapter')
            ->disableOriginalConstructor()
            ->setMethods(['getHeader'])
            ->getMock();
    }

    public function dataCodes() {
        return [
            [
                Result::FAILURE_IDENTITY_NOT_FOUND,
                'InvalidUsername'
            ],
            [
                Result::FAILURE_CREDENTIAL_INVALID,
                'InvalidPassword'
            ],
            [
                Result::SUCCESS,
                'SUCCESS'
            ],
            [
                'bla',
                'Uncategorized'
            ],
        ];
    }

    /**
     * @dataProvider dataCodes
     * @param String $code
     * @param String $expectedType
     */
    public function testCustomExceptions( $code, $expectedType ) {
        $result = $this->getMockBuilder('Zend\Authentication\Result')
            ->disableOriginalConstructor()
            ->setMethods(['getCode', 'getMessages'])
            ->getMock();
        $result->expects($this->at(0))
            ->method('getCode')
            ->will($this->returnValue($code));
        if ( $expectedType != 'SUCCESS' ) {
            if ( $expectedType == 'Uncategorized' ) {
                $result->expects($this->at(1))
                    ->method('getMessages')
                    ->will($this->returnValue(['message1', 'message2']));
            }
            $this->setExpectedException('Authentication\Model\Exception\Authentication\\' . $expectedType);
        }
        $authAdapter = new AuthenticationAdapter($this->adapter, new PasswordManager());

        $authAdapter->throwCustomExceptions($result);
    }

    public function dataTests() {
        return [
            [
                [
                    'username' => 'test',
                    'password' => 'test',
                ],
                true
            ],
            [
                [
                ],
                false
            ],
        ];
    }

    /**
     * @dataProvider dataTests
     * @param DataContainer $data
     * @param bool $isValid
     */
    public function testSetUp( $data, $isValid ) {
        if ( !$isValid ) {
            $this->setExpectedException('Authentication\Model\Exception\Authentication\MissingParameters');
        }
        $authAdapter = new AuthenticationAdapter($this->adapter, new PasswordManager());

        $authAdapter->setUp($data);
        if ( $isValid ) {
            $this->assertEquals($data['username'], $authAdapter->getIdentity());
            $this->assertEquals($data['password'], $authAdapter->getCredential());
        }
    }

    public function testAuthenticateValidateResultSet() {

        $authAdapter = new AuthenticationAdapter($this->adapter, new PasswordManager());

        $this->assertEquals(
            true, $authAdapter->publicValidateResultSet([0 => ['id' => 'ku']])
        );
        $this->assertEquals(
            'ku', $authAdapter->getLoginId()
        );
    }

    public function testGetCallback() {
        $pm          = new PasswordManager();
        $authAdapter = new AuthenticationAdapter($this->adapter, $pm);

        $credentialCallback = function ($passwordInDatabase, $passwordProvided) use ($pm) {
            return $pm
                    ->validate(
                        $passwordInDatabase, $passwordProvided
            );
        };
        $this->assertEquals(
            $credentialCallback, $authAdapter->getCallBack()
        );
    }

    public function testSelect() {
        $sm = AuthenticationBootstrap::getServiceManager();

        $adapter     = $sm->get('dbAdminAdapter');
        $pm          = new PasswordManager();
        $authAdapter = new AuthenticationAdapter($this->adapter, $pm);
        $authAdapter->setUp([
            'username' => 'test',
            'password' => 'test',
            ]
        );
        $authAdapter->setTableName('test');

        $expected = new Select();
        $actual   = $authAdapter->getCreateSelect();
        $expected
            ->from('test')
            ->where(['username' => 'test'])
            ->where([
                'login_status' => 'ACTIVE'
        ]);
        $this->assertEquals(
            $expected->getSqlString($adapter->getPlatform()), $actual->getSqlString($adapter->getPlatform())
        );
    }

}

class Stub extends DataContainer {

    public $username;
    public $password;
    protected static $keyMap = [
        'username' => 'username',
        'password' => 'password',
    ];

}
