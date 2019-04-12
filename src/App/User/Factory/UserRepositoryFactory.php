<?php


namespace Check\App\User\Factory;


use Check\App\User\Repository\UserAuthentificationRepository;
use Check\App\User\Repository\UserRepository;
use Check\Globals\Database;

class UserRepositoryFactory
{
    /**
     * @param Database $db
     * @return UserAuthentificationRepository
     */
    public function createUserAuthentificationRepository(Database $db): UserAuthentificationRepository
    {
        return new UserAuthentificationRepository($db);
    }

    /**
     * @param Database $db
     * @return UserRepository
     */
    public function createUserRepository(Database $db): UserRepository
    {
        return new UserRepository($db);
    }
}