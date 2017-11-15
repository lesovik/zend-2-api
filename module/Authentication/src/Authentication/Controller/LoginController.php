<?php

namespace Authentication\Controller;

use Authentication\Model\Service\TokenStorage;
use Core\Controller\RestfulJsonController;
use Authentication\Model\View\Authentication as JsonViewModel;
use Zend\Db\Adapter\Driver\Pgsql\Pgsql;
use Zend\Http\Response as Response;
use Zend\Authentication\AuthenticationService;
use Core\Exception;

class LoginController extends RestfulJsonController
{
    /** @var AuthenticationService */
    protected $authService;
    /** @var JsonViewModel */
    protected $json;

    public function __construct(AuthenticationService $authService, JsonViewModel $json)
    {
        $this->authService = $authService;
        $this->json = $json;
    }

    /**
     * @SWG\Resource(
     *    resourcePath="/authentication/login",
     *   	@SWG\Api(
     *   		@SWG\Operation(
     *            method="POST",
     *            summary="Logs in user by and returns token ",
     *   			@SWG\Parameters(
     *   				@SWG\Parameter(
     *                    name="username",
     *                    paramType="body",
     *                    dataType="string",
     *                    required="true",
     *                    description="Username"
     *                ),
     *   				@SWG\Parameter(
     *                    name="password",
     *                    paramType="body",
     *                    dataType="string",
     *                    required="true",
     *                    description="Password"
     *                )
     *            ),
     *        )
     *    )
     *   )
     * @method POST
     * @return JsonViewModel
     */
    public function create($data)
    {
        /** @var AuthenticationService $service */
        $service = $this->authService;
        /** @var Pgsql $adapter */
        $adapter = $service->getAdapter();
        /** @var TokenStorage $storage */
        $storage = $service->getStorage();
        try {
            $adapter->setUp($data);
            $service->authenticate();
            $storage->setIdentity($adapter->getLoginId());
            $this->json->setSuccess($storage->getToken());
        } catch (Exception $ex) {
            $this->json->setFail($ex, $this->getEvent(), Response::STATUS_CODE_400);
        }
        return $this->json;
    }

    public function logoutAction()
    {
        $this->authService->clearIdentity();
        return $this->json;
    }

    public function options()
    {
        return $this->json;
    }

}
