<?php

namespace Authentication\Model\Service;

use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Authentication\Result;
use Authentication\Model\Service\PasswordManager;
use Authentication\Model\Exception\Authentication as Exception;


/**
 * Authentication Adapter
 *
 * @author Dmitry Lesov
 */
class AuthenticationAdapter extends CallbackCheckAdapter
{

    /**
     * @var PasswordManager $passwordManager
     * @var int $loginId
     */
    private $passwordManager;
    private $loginId;

    /**
     *
     * __construct()
     *
     * injects dependencies and calls parent constructor
     *
     * @param DbAdapter $dbAdapter
     * @param PasswordManager $passwordManager
     */
    public function __construct(DbAdapter $dbAdapter,
                                PasswordManager $passwordManager)
    {
        $this->passwordManager = $passwordManager;
        parent::__construct(
            $dbAdapter, 'login', 'username', 'password', $this->getCallBack()
        );
    }

    /**
     * override parent function to throw custom exception
     *
     * @return AuthenticationResult
     */
    public function authenticate()
    {
        $authResult = parent::authenticate();
        $this->throwCustomExceptions($authResult);
        return $authResult;
    }

    /**
     * throws custom autheticate exceptions
     *
     * @throws Exception if answering the authentication query is impossible
     */
    public function throwCustomExceptions($authResult)
    {
        if ($authResult instanceof Result) {
            switch ($authResult->getCode()) {
                case Result::FAILURE_IDENTITY_NOT_FOUND:
                    throw new Exception\InvalidUsername();

                case Result::FAILURE_CREDENTIAL_INVALID:
                    throw new Exception\InvalidPassword();

                case Result::SUCCESS:
                    break;

                default:
                    throw new Exception\Uncategorized(implode(
                        ', ', $authResult->getMessages()
                    ));
            }
        }
    }

    /**
     * throws custom autheticate exceptions
     *
     * @throws Exception\MissingParameters if answering the authentication query is impossible
     * @return void
     */
    public function setUp($data)
    {
        if (empty($data['username']) || empty($data['password'])) {
            throw new Exception\MissingParameters();
        }
        $this->setIdentity($data['username'])->setCredential($data['password']);
    }

    /**
     *
     * @return int
     */
    public function getLoginId()
    {
        return $this->loginId;
    }

    /**
     *
     * @return PasswordManager
     */
    public function getPasswordManager()
    {
        return $this->passwordManager;
    }

    /**
     * _authenticateValidateResultSet() - intercepts parent method to hijack primary key value
     *
     * @param  array $resultIdentities
     * @return bool|\Zend\Authentication\Result
     */
    protected function authenticateValidateResultSet(array $resultIdentities)
    {
        $result = parent::authenticateValidateResultSet($resultIdentities);
        if ($result === true) {
            $this->loginId = $resultIdentities[0]['id'];
        }
        return $result;
    }

    /**
     * publicValidateResultSet() - public override for testing of authenticateValidateResultSet
     *
     * @param  array $resultIdentities
     * @return bool|\Zend\Authentication\Result
     */
    public function publicValidateResultSet(array $resultIdentities)
    {
        return $this->authenticateValidateResultSet($resultIdentities);
    }

    /**
     *
     * _getCallBack()
     *
     * returns callback to Password Manager
     *
     * @return callable
     */
    public function getCallBack()
    {
        $credentialCallback = function ($passwordInDatabase, $passwordProvided) {
            return $this->passwordManager
                ->validate(
                    $passwordInDatabase, $passwordProvided
                );
        };
        return $credentialCallback;
    }

    /**
     * _authenticateCreateSelect()
     *
     * override appends additional parameters to parent
     * authentication query
     *
     * @return Zend\Db\Sql\Select
     */
    protected function authenticateCreateSelect()
    {
        /**
         * @var Zend\Db\Sql\Select $dbSelect
         */
        $dbSelect = parent::authenticateCreateSelect();
        $dbSelect
            ->where([
                'login_status' => 'ACTIVE'
            ]);
        return $dbSelect;
    }

    /**
     * getCreateSelect()
     *
     * public function to test authenticateCreateSelect()
     *
     * @return Zend\Db\Sql\Select
     */
    public function getCreateSelect()
    {
        return $this->authenticateCreateSelect();
    }

}
