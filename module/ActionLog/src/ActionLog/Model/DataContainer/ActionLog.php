<?php

/*
 * Zend 2 API
 */

namespace ActionLog\Model\DataContainer;

use Core\DataContainer;

/**
 * 
 *  ActionLog
 *
 * @author Dmitry Lesov
 */
class ActionLog extends DataContainer {

    protected $actionLogId   = null;
    protected $tokenId       = null;
    protected $route         = null;
    protected $method        = null;
    protected $data          = null;
    protected $responseCode  = null;
    protected static $keyMap = [
        'actionLogId'  => 'id',
        'tokenId'      => 'token_id',
        'route'        => 'route',
        'method'       => 'method',
        'data'         => 'data',
        'responseCode' => 'response_code'
    ];
    protected $sort          = 'id';
    protected $order         = 'ASC';
    protected $skip          = 0;
    protected $limit         = 20;
    
    public function __construct( array $data = null ) {
        if ( !empty($data['data']) ) {
            $data['data'] = serialize($data['data']);
        }
        parent::__construct($data);
    }

    /**
     * Getters
     */
    public function getActionLogId() {
        return $this->actionLogId;
    }

    public function getTokenId() {
        return $this->tokenId;
    }

    public function getRoute() {
        return $this->route;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getData() {
        return $this->data;
    }

    public function getResponseCode() {
        return $this->responseCode;
    }

    public function getSort() {
        return $this->sort;
    }

    public function getOrder() {
        return $this->order;
    }

    public function getSkip() {
        return $this->skip;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function getArrayCopy() {
        $arrayCopy = parent::getArrayCopy();
        if ( !empty($arrayCopy['data']) ) {
            $arrayCopy['data'] = unserialize($arrayCopy['data']);
        }
        return $arrayCopy;
    }

}
