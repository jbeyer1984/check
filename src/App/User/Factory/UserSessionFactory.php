<?php


namespace Check\App\User\Factory;


use Check\App\User\Session\UserAuthentificationSession;
use Check\Globals\Session;

class UserSessionFactory
{
    /**
     * @param Session $session
     * @return UserAuthentificationSession
     */
    public function createUserAuthentificationSession(Session $session): UserAuthentificationSession
    {
        return new UserAuthentificationSession($session);
    }
}