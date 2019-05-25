<?php


namespace Check\App\User\Session;


use Check\App\User\LoggedInUser;
use Check\App\User\UserSession;
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
                'user_id' => $loggedInUser->getId(),
            ]
        );
    }

    /**
     * @param UserSession $userSession
     * @return bool
     */
    public function existsByUserSession(UserSession $userSession): bool
    {
        $result = $this->session->query([
            'user_id',
        ]);
        
        return ($userSession->getId() === $result['user_id']);
    }

    public function queryUserId(): int
    {
        $userData = $this->session->query(
            [
                'user_id',
            ]
        );
        
        return $userData['id'];
    }

}