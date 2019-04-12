<?php


namespace Check\App\User\Session;


use Check\App\User\LoggedInUser;
use Check\Globals\Session;

class UserAuthentificationSession
{
    /**
     * @var Session
     */
    private $session;

    /**
     * UserAuthentificationSession constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function createByLoggedInUser(LoggedInUser $loggedInUser)
    {
        $this->session->addAll(
            [
                'id' => $loggedInUser->getId(),
                'email' => $loggedInUser->getEmail(),
                'password' => $loggedInUser->getPassword()
            ]
        );
    }

    public function exists(): bool
    {
        return $this->session->existsArrayKeys(
            [
                'id',
                'email',
                'password'
            ]
        );
    }

    public function queryUserId(): int
    {
        $userData = $this->session->query(
            [
                'id',
            ]
        );
        
        return $userData['id'];
    }

}