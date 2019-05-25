<?php


namespace Check\App\User;


use Check\Persistence\MapperInterface;

class LoggedInUserMapper implements MapperInterface
{
    /**
     * @var LoggedInUser
     */
    private $loggedInUser;

    /**
     * LoggedInUserMapper constructor.
     * @param LoggedInUser $loggedInUser
     */
    public function __construct(LoggedInUser $loggedInUser)
    {
        $this->loggedInUser = $loggedInUser;
    }

    /**
     * @return array
     */
    public function getMap(): array
    {
        $array = [
            'id' => 0
        ];
        if (!empty($this->loggedInUser->getId())) {
            $array = [
                'id' => $this->loggedInUser->getId(),
            ];
        }
        
        $array = array_merge(
            $array,
            [
                'name' => $this->loggedInUser->getName(),
                'email' => $this->loggedInUser->getEmail(),
                'password' => $this->loggedInUser->getPassword(),
            ]
        );
        
        return $array;
    }


}