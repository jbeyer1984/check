<?php


namespace Check\App\User\Factory;


use Check\App\User\UserSession;
use Check\App\User\UserSessionMapper;
use DI\Container;

class UserSessionFactory
{
    /**
     * @var Container
     */
    private $container;

    /**
     * UserSessionFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $userSessionRecordSet
     * @return UserSession
     */
    public function createUserSessionByRecordSet(array $userSessionRecordSet): UserSession
    {
        $id = $userSessionRecordSet['id'];
        $userId = $userSessionRecordSet['user_id'];
        $sessionId = $userSessionRecordSet['session_id'];
        
        return $this->createUserSession($id, $userId, $sessionId);
    }

    /**
     * @param int $id
     * @param string $userId
     * @param string $sessionId
     * @return UserSession
     */
    public function createUserSession(int $id, string $userId, string $sessionId): UserSession
    {
        return new UserSession($id, $userId, $sessionId);
    }

    public function createUserSessionPrototype(string $userId, string $sessionId): UserSession
    {
        return new UserSession(0, $userId, $sessionId);
    }

    /**
     * @return UserSession
     */
    public function createUserSessionDummy()
    {
        return new UserSession(0, 0, 0);
    }

    /**
     * @param UserSession $userSession
     * @return UserSessionMapper
     */
    public function createUserSessionMapper(UserSession $userSession): UserSessionMapper
    {
        return new UserSessionMapper($userSession);
    }
}