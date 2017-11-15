<?php
/*
 * Zend 2 API
 */

namespace AdminUser\Model\Service\AdminUser;

use AdminUser\Model\DataContainer\AdminUser\ForInsert as AdminUser;
use AdminUser\Model\Service\AdminUser as Service;
use AdminUser\Model\Sql\Insert\AdminUser as Insert;
use Zend\Db\Adapter\Driver\Pgsql\Connection;

/**
 * 
 * AdminUser Create Class - to execute all needed queries
 *
 * @author Dmitry Lesov
 */
class Create extends Service {

    /**
     * 
     * execute()
     * 
     * attempts to execute all the queries
     *
     * @param array $parameters
     * @throws \Exception
     * @return boolean 
     */
    public function execute( array $parameters ) {
        $this->data = $this->singleDatumToArray($parameters);

        $connection = $this->executer->getAdapter()->getDriver()->getConnection();
        /** @var Connection $connection*/
        $connection->beginTransaction();
        try {
            foreach ($this->data as $key => $adminUserData) {
                if ( !$this->isValid($adminUserData) ) {
                    $connection->rollback();
                    return false;
                }

                $this->data[$key] = $this->doInsert($adminUserData);
            }
            $connection->commit();
            return true;
        } catch (\Exception $ex) {
            $connection->rollback();
            throw $ex;
        }
    }

    /**
     * 
     * 
     * returns the result of service execution 
     * 
     *
     * @return array
     */
    public function getData() {
        if ( count($this->data) == 1 ) {
            return $this->data[0];
        } else {
            return $this->data;
        }
    }

    /**
     * 
     * 
     * overrides parent to account for either company name or company id being required
     * 
     * since company_id is marked as required in the keyMap it 
     * loops through validators and unsets it in company_name exists
     * 
     * 
     * @param array $data
     * @return boolean
     */
    protected function isValid( array $data ) {
        
        $this->validator->setUp(new AdminUser(), $this->executer->getAdapter());
       
        $this->validator->setData($data);
       
        if ( !$this->validator->isValid() ) {
            return false;
        }
        return true;
    }

    /**
     * 
     * 
     * performs all the needed queries
     *
     * @return array
     */
    protected function doInsert( array $data ) {
        $adminUser = new AdminUser($data);
        $this->executer->execute(
            new Insert\Login(
            $adminUser, $this->serviceManager->get('PasswordManager')
        ));
        $adminUser->setLoginId($this->executer->getLastGeneratedValue('login_id_seq'));
        return $adminUser->getArrayCopy();
    }


}
