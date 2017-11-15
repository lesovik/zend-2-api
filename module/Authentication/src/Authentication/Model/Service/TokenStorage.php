<?php

namespace Authentication\Model\Service;

use Zend\Authentication\Storage\StorageInterface;
use Zend\Http\PhpEnvironment\Request;
use Core\Db\SqlExecuter;
use Authentication\Model\Sql;
use Authentication\Model\DataContainer\Criteria;
use Authentication\Model\DataContainer\AccessToken;

class TokenStorage implements StorageInterface
{

    /**
     * @var  Request $request
     * @var  SqlExecuter $executer
     * @var  AccessToken $token
     */
    protected $request;
    protected $executer;
    protected $token;

    const TOKEN_LIFESPAN = 3200;
    const TOKEN_LENGTH = 24;

    /**
     * injects request and executer
     *
     * @param  Request $request
     * @param  SqlExecuter $executer
     */
    public function __construct(Request $request, SqlExecuter $executer)
    {
        $this->request = $request;
        $this->executer = $executer;
    }

    /**
     * Returns true if and only if storage is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->read());
    }

    /**
     * Returns the contents of storage
     * Behavior is undefined when storage is empty.
     *
     * @return mixed
     */
    public function read()
    {
        $supplied = $this->request->getHeader('authorization');
        if ($supplied && strpos(trim($supplied->getFieldValue()), 'Bearer ') === 0) {
            $criteria = new Criteria\AccessToken([
                'token' => str_replace('Bearer ', '', trim($supplied->getFieldValue()))
            ]);
            $result = $this->executer->execute(
                new Sql\Select\AccessToken($criteria),
                new AccessToken()
            );
            if (count($result)) {
                $this->token = $result->current();
                return $this->token;
            }
        }
        return false;
    }

    /**
     * dummy since adapter service tries to use it with identity key ('username')
     * we need login_id to create storage
     *
     * @param string $loginId
     * @return void
     */
    public function write($loginId)
    {

    }

    /**
     * sets identity
     *
     * @param int $loginId
     * @return void
     */
    public function setIdentity($loginId)
    {
        $this->token = new AccessToken([
            'login_id' => $loginId,
            'token' => $this->generateToken(),
            'ip' => $this->request->getServer('REMOTE_ADDR'),
            'expires' => date(
                "Y-m-d H:i",
                time() + self::TOKEN_LIFESPAN
            ),
        ]);

        $this->executer->execute(
            new Sql\Insert\AccessToken($this->token)
        );
    }

    /**
     * generates random token
     *
     * @return string
     */
    public function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(self::TOKEN_LENGTH / 2));
    }

    /**
     * returns current Access Token onbject
     *
     * @return AccessToken
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Clears contents from storage
     *
     * @return void
     */
    public function clear()
    {
        $this->token = null;
    }

}
