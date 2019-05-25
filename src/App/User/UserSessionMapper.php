<?php


namespace Check\App\User;


use Check\Persistence\MapperInterface;

class UserSessionMapper implements MapperInterface
{
    /**
     * @var UserSession
     */
    private $userSession;

    /**
     * UserSessionMapper constructor.
     * @param UserSession $userSession
     */
    public function __construct(UserSession $userSession)
    {
        $this->userSession = $userSession;
    }

    /**
     * @return array
     */
    public function getMap(): array
    {
        $array = [
            'id' => 0
        ];
        if (!empty($this->userSession->getId())) {
            $array = [
                'id' => $this->userSession->getId(),
            ];
        }
        return array_merge(
            $array,
            [
                'user_id' => $this->userSession->getUserId(),
                'session_id' => $this->userSession->getSessionId()
            ]
        );
    }


}