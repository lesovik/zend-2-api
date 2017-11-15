<?php

/*
 * Zend 2 API
 */

namespace AdminUser\Model\Service\AdminUser;

use AdminUser\Model\DataContainer\AdminUser\ForUpdate as AdminUser;
use AdminUser\Model\Service\AdminUser as Service;
use AdminUser\Model\Sql\Update\AdminUser as Sql;

/**
 *
 *  AdminUser updater - to execute all needed queries
 *
 * @author Dmitry Lesov
 */
class Update extends Service
{

    /**
     * @var Service\Get $adminUserGetter
     */
    protected $adminUserGetter;
    protected $modified;

    /**
     *
     * execute()
     *
     * attempts to execute all the queries
     * @throws \Exception
     * @param array $parameters
     * @return boolean
     */
    public function execute(array $parameters)
    {
        /**
         * @var Service\Get $adminUserGetter
         */
        $this->adminUserGetter = $this->serviceManager->get('AdminUserGet');
        $this->data = $this->singleDatumToArray($parameters);
        $connection = $this->executer->getAdapter()->getDriver()->getConnection();
        $connection->beginTransaction();
        try {
            foreach ($this->data as $key => $data) {
                $data['id'] = (!$this->id && !empty($data['id'])) ? $data['id'] : $this->id;
                if (!$this->isValid($data)) {
                    $connection->rollback();
                    return false;
                }
                $this->data[$key] = $this->doUpdate($data);
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
     * @return array
     */
    public function getData()
    {
        if (count($this->data) == 1) {
            return $this->data[0];
        } else {
            return $this->data;
        }
    }

    /**
     *
     *
     * performs all the needed queries
     *
     * @return array
     */
    protected function doUpdate(array $data)
    {
        $adminUser = new AdminUser($data);

        $this->executer->execute(
            new Sql\Login($adminUser, $this->passwordManager)
        );

        return $adminUser->getArrayCopy();
    }

    /**
     *
     *
     * validates passed array agaist object schema set by validator
     * @param array $data
     * @return boolean
     */
    protected function isValid(array $data)
    {
        if (!$this->adminUserGetter->execute(['id' => $data['id']])) {
            $this->validator = $this->adminUserGetter->getValidator();
            return false;
        } else {
            $existing = $this->adminUserGetter->getData();
            foreach ($data as $key => $val) {
                if ($key != 'id' && !empty($existing[$key]) && $val == $existing[$key]) {
                    unset($data[$key]);
                }
            }
        }
        $this->modified = $data;
        $this->validator->setUp(new AdminUser(), $this->executer->getAdapter());
        $this->validator->setData($this->modified);
        if (!$this->validator->isValid()) {
            return false;
        }
        return true;
    }

}
