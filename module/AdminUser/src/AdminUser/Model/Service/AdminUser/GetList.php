<?php

/*
 * Zend 2 API
 */

namespace AdminUser\Model\Service\AdminUser;

use AdminUser\Model\DataContainer\AdminUser;
use AdminUser\Model\Service\AdminUser as Service;
use AdminUser\Model\Sql\Select\AdminUser\Filter as Select;
use AdminUser\Model\DataContainer\AdminUser\ForSelect;
use Zend\Paginator\Paginator as ZendPaginator;
use Zend\Paginator\Adapter\ArrayAdapter;

/**
 *
 * AdminUser lister
 *
 * @author Dmitry Lesov
 */
class GetList extends Service
{

    /**
     * ForSelect $criteria
     */
    protected $criteria = null;
    protected $totalResults = 0;

    /**
     *
     * execute()
     *
     * attempts to build select query
     *
     * @param array $parameters
     * @return boolean
     */
    public function execute(array $parameters)
    {
        $this->criteria = new ForSelect($parameters);

        $this->validator->setUp($this->criteria, $this->executer->getAdapter());
        if (!$this->isValid($parameters)) {
            return false;
        }
        $resultSet = $this->executer->execute(
            new Select($this->criteria), new AdminUser()
        );

        $data = [];
        if (count($resultSet)) {
            foreach ($resultSet as $adminUser) {
                $data[] = $adminUser->getArrayCopy();
            }
        }
        $paginator = new ZendPaginator(new ArrayAdapter($data));
        //skip starts from 0 pages start from 1
        $paginator->setCurrentPageNumber($this->criteria->getSkip() + 1);
        $this->totalResults = $paginator->getItemCount($data);
        $paginator->setItemCountPerPage($this->criteria->getLimit());
        $this->data = $paginator;
        return true;
    }

    public function getCriteriaArray()
    {
        if (!$this->criteria) {
            $this->criteria = new ForSelect();
        }
        return array_merge($this->criteria->getArrayCopy(), [
            'total_results' => $this->totalResults
        ]);
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
        $this->validator->setData($data);
        if (!$this->validator->isValid()) {
            return false;
        }
        return true;
    }

}
