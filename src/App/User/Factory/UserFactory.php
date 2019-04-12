<?php


namespace Check\App\User\Factory;


use Check\App\User\Authentification\UserCredentials;
use Check\App\User\LoggedInUser;
use Check\App\User\Repository\UserAuthentificationRepository;
use Check\App\User\Repository\UserRepository;
use Check\App\User\Session\UserAuthentificationSession;
use Check\Globals\Database;
use Check\Globals\Session;
use DI\Container;

class UserFactory
{
    public function createLoggedInUser(
        UserAuthentificationRepository $userAuthentificationRepository, UserCredentials $userCredentials
    ): LoggedInUser
    {
        $userSet = $userAuthentificationRepository->getFetchedUserData($userCredentials);
        $id = $userSet[0]['id'];
        $name = $userSet[0]['name'];
        $email = $userSet[0]['email'];
        $password = $userSet[0]['password'];
        
        return new LoggedInUser($id, $name, $email, $password);
    }

    /**
     * @param Session $session
     * @param Database $db
     * @return LoggedInUser
     */
    public function createLoggedInUserByPersistence(Session $session, Database $db)
    {
        $container = new Container();
        $userSessionFactory = new UserSessionFactory();
        $userSession = $userSessionFactory->createUserAuthentificationSession($session);
//        $userSession = $container->get(UserAuthentificationSession::class);
        $userId = $userSession->queryUserId();
//        $userRepositoryFactory = new UserRepositoryFactory();
//        $userRepository = $userRepositoryFactory->createUserRepository($db);
        $userRepository = $container->get(UserRepository::class);
        $userSet = $userRepository->getFetchedUserSetById($userId);
        $id = $userSet[0]['id'];
        $name = $userSet[0]['name'];
        $email = $userSet[0]['email'];
        $password = $userSet[0]['password'];
        $loggedInUser = new LoggedInUser($id, $name, $email, $password);
        
        return $loggedInUser;
    }
}