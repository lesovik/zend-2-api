<?php

/*
 * Zend 2 API
 */

namespace AdminUser\Model\Service;

use Core\Db\SqlExecuter;
use Zend\ServiceManager\ServiceManager;
use Core\Form\DataValidator;

/**
 *
 *  Abstract AdminUser Service
 *
 * @author Dmitry Lesov
 */
abstract class AdminUser
{

    protected $serviceManager;

    /** @var SqlExecuter  */
    protected $executer;
    protected $passwordManager;
    /**
     *
     * @var DataValidator $validator
     */
    protected $validator;
    protected $id;
    protected $data;

    /**
     *
     * __construct()
     *
     * injects Service manager and parameters and sets other services for convenience
     *
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        $this->executer = $serviceManager->get('ExecuterAdmin');
        $this->passwordManager = $serviceManager->get('PasswordManager');
        $this->validator = $serviceManager->get('DataValidator');
    }

    /**
     * @param array $parameters
     * @return bool
     */
    abstract public function execute(array $parameters);

    /**
     * returns the result of service execution
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return DataValidator
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     *
     *
     * validates passed array against object schema set by validator
     * @param array $data
     * @return boolean
     */
    protected function isValid(array $data)
    {
        $isValid = false;

        $this->validator->setData($data);
        if ($this->validator->isValid()) {
            $isValid = true;
        }

        return $isValid;
    }

    /**
     * checks if $parameters is an associative array and if so wraps it in an array
     * since services deal with sets of arrays
     *
     * @param array
     * @return array
     */
    protected function singleDatumToArray(array $parameters)
    {
        if (array_keys($parameters) !== range(0, count($parameters) - 1)) {
            return [$parameters];
        }
        return $parameters;
    }

}
