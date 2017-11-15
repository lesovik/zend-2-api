<?php

/*
 * Zend 2 API
 */

namespace ActionLog\Model\Service;

use Zend\ServiceManager\ServiceManager;
use Core\Form\DataValidator;

/**
 * 
 *  Abstract ActionLog Service
 *
 * @author Dmitry Lesov
 */
abstract class ActionLog {

    protected $serviceManager;
    protected $executer;
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
     * @return void 
     */
    public function __construct( ServiceManager $serviceManager ) {
        $this->serviceManager  = $serviceManager;
        $this->executer        = $serviceManager->get('ExecuterAdmin');
        $this->validator       = $serviceManager->get('DataValidator');
    }

    /**
     * 
     * 
     * attempts to execute the ActionLog service
     *
     * @param array $parameters
     * @param int $id [optional]
     * @return bool 
     */
    abstract public function execute( array $parameters );

    /**
     * 
     * 
     * returns the result of service execution
     *
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * 
     * 
     * sets id property
     *
     * @param int $id
     * @return array
     */
    public function setId( $id ) {
        $this->id = $id;
    }

    /**
     * 
     * returnd the instance of DataValidator
     *
     * @return DataValidator 
     */
    public function getValidator() {
        return $this->validator;
    }

    /**
     * 
     * 
     * validates passed array agaist object schema set by validator
     * @param array $data
     * @return boolean
     */
    protected function isValid( array $data ) {
        $this->validator->setData($data);
        if ( !$this->validator->isValid() ) {
            return false;
        }
        return true;
    }
}
