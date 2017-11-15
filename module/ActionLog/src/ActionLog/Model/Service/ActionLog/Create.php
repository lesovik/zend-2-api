<?php
namespace ActionLog\Model\Service\ActionLog;

use ActionLog\Model\DataContainer\ActionLog\ForInsert as ActionLog;
use ActionLog\Model\Service\ActionLog as Service;
use ActionLog\Model\Sql\Insert\ActionLog as Insert;
use Authentication\Model\Service\TokenStorage;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Json\Json;

/**
 *
 * ActionLog Create - creates a log in db for each request
 *
 * @author Dmitry Lesov
 */
class Create extends Service
{

    /**
     *
     * log()
     *
     * gathers proper data from event and executes depending on conditions
     *
     * @param MvcEvent $event
     * @return bool
     */
    public function log(MvcEvent $event)
    {

        /** @var AuthenticationService $authService */
        $authService = $this->serviceManager->get('AuthenticationService');
        $config = $this->serviceManager->get('Config');

        /** @var TokenStorage $storage */
        $storage =$authService->getStorage();

        /** @var Request $request */
        $request = $event->getRequest();
        /** @var Response $response */
        $response = $event->getResponse();

        $method = $request->getMethod();
        $content = ($request->getContent()) ?: '{}';
        $data = $this->dataSecure(Json::decode($content, Json::TYPE_ARRAY));
        $routeMatch = $event->getRouteMatch();
        if (
            $routeMatch &&
            !in_array($routeMatch->getMatchedRouteName(), $config['openRoutes'])
            && $authService->hasIdentity() &&
            in_array($method, $config['loggableMethods']) &&
            ($response->isSuccess() || $config['successOnly'] === false)
        ) {
            if (!$this->execute([
                'token_id' => $storage->getToken()->getId(),
                'method' => $method,
                'route' => $request->getUri()->getPath(),
                'data' => $data,
                'response_code' => $response->getStatusCode(),
            ])) {
                return false;
            }
        }
        return true;
    }

    /**
     *
     * execute()
     *
     * attempts to execute all the queries
     *
     * @param array $parameters
     * @return boolean
     */
    public function execute(array $parameters)
    {

        if (!$this->isValid($parameters)) {
            return false;
        }
        $this->data = $this->doInsert($parameters);

        return true;
    }

    /**
     *
     *
     * recursively masks all sensitive data
     *
     * @param array | null $data
     * @return array | null
     */
    protected function dataSecure($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if (is_array($val)) {
                    $data[$key] = $this->dataSecure($val);
                } else {
                    if (in_array($key, $this->serviceManager->get('Config')['maskedFields'])) {
                        $data[$key] = $this->serviceManager->get('Config')['mask'];
                    }
                }
            }
        }
        return $data;
    }

    /**
     *
     *
     * overrides parent to account for either company name or company id being required
     *
     *
     * @param array $data
     * @return boolean
     */
    protected function isValid(array $data)
    {

        $this->validator->setUp(new ActionLog(), $this->executer->getAdapter());
        $this->validator->setData($data);
        if (!$this->validator->isValid()) {
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
    protected function doInsert(array $data)
    {
        $actionLog = new ActionLog($data);
        $this->executer->execute(new Insert($actionLog));
        return $actionLog->getArrayCopy();
    }

}
