<?php


namespace Check\App\User\Authentification;


use Check\App\User\UserSession;
use Check\Globals\Session;

class SessionCredentials
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var UserSession
     */
    private $userSession;

    /**
     * SessionCredentials constructor.
     * @param Session $session
     * @param UserSession $userSession
     */
    public function __construct(Session $session, UserSession $userSession)
    {
        $this->session     = $session;
        $this->userSession = $userSession;
    }

    public function getUserId()
    {
        
    }

}