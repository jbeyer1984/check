<?php


namespace Check\App\User\Factory;


use Check\App\User\LoggedInUser;
use Check\App\User\LoggedInUserMapper;
use DI\Container;

class UserFactory
{
    /**
     * @var Container
     */
    private $container;

    /**
     * UserFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function createLoggedInUserByRecordSet(array $userRecordSet): LoggedInUser
    {
        $id = $userRecordSet['id'];
        $name = $userRecordSet['name'];
        $email = $userRecordSet['email'];
        $password = $userRecordSet['password'];

        return $this->createLoggedInUser($id, $name, $email, $password);
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $email
     * @param string $password
     * @return LoggedInUser
     */
    public function createLoggedInUser(int $id, string $name, string $email, string $password): LoggedInUser
    {
        return new LoggedInUser($id, $name, $email, $password);
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @return LoggedInUser
     */
    public function createLoggedInUserPrototype(string $name, string $email, string $password): LoggedInUser
    {
        return $this->createLoggedInUser(0, $name, $email, $password);
    }

    /**
     * @return LoggedInUser
     */
    public function createLoggedInUserDummy()
    {
        return $this->createLoggedInUser(0, '', '', '');
    }

    /**
     * @param LoggedInUser $loggedInUser
     * @return LoggedInUserMapper
     */
    public function createLoggedInUserMapper(LoggedInUser $loggedInUser): LoggedInUserMapper
    {
        return new LoggedInUserMapper($loggedInUser);
    }
}