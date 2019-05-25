<?php


namespace Check\App\User;


class UserSession
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $sessionId;

    /**
     * UserSession constructor.
     * @param int $id
     * @param int $userId
     * @param string $sessionId
     */
    public function __construct(int $id, int $userId, string $sessionId)
    {
        $this->id        = $id;
        $this->userId    = $userId;
        $this->sessionId = $sessionId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }
}