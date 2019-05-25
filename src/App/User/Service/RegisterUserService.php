<?php


namespace Check\App\User\Service;


use Check\App\User\Authentification\UserCredentials;
use Check\App\User\Factory\UserFactory;
use Check\App\User\Factory\UserSessionFactory;
use Check\App\User\LoggedInUser;
use Check\App\User\Repository\UserRepository;
use Check\App\User\Repository\UserSessionRepository;
use Check\App\User\Session\UserAuthentificationSession;
use Check\Globals\Session;
use Check\Persistence\Condition\Condition;
use Check\Persistence\Condition\ConditionContainer\ConditionContainer;
use Check\Persistence\Condition\ConditionContainer\ConditionWrapper;
use DI\Container;

class RegisterUserService
{
    /**
     * @var Container
     */
    private $container;

    /**
     * RegisterUserService constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param UserCredentials $userCredentials
     * @return LoggedInUser
     * @throws \Exception
     */
    public function getAuthorizedUserByCredentials(UserCredentials $userCredentials): LoggedInUser
    {
        $userRepository = $this->container->get(UserRepository::class);
        $conditionContainer = (ConditionContainer::And())
            ->add(ConditionWrapper::wrapLower(Condition::operator('email', '=', $userCredentials->getEmail())))
        ;
        
        $loggedInUsers = $userRepository->findBy($conditionContainer);
        
        if (1 === count($loggedInUsers)) {
            $loggedInUser = $loggedInUsers[0];

            $verify = $this->verifyUser($userCredentials, $loggedInUser);

            if ($verify) {
                return $loggedInUser;
            }
        }
        
        $loggedInUserDummy = $this->container->get(UserFactory::class)->createLoggedInUser(0, '', '', '');
        
        return $loggedInUserDummy;
    }

    /**
     * @param LoggedInUser $loggedInUser
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function authorizeSessionByLoggedInUser(LoggedInUser $loggedInUser)
    {
        $userAuthentificationSession = $this->container->get(UserAuthentificationSession::class);
        $userAuthentificationSession->createByLoggedInUser($loggedInUser);
        $session = $this->container->get(Session::class);
        $this->authorizeSession($loggedInUser);
        $this->authorizeDbSession($loggedInUser, $session);
    }

    /**
     * @return LoggedInUser
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Exception
     */
    public function getAuthorizedUserBySession(): LoggedInUser
    {
        $session               = $this->container->get(Session::class);
        $userSessionRepository = $this->container->get(UserSessionRepository::class);
        $userSessions          = $userSessionRepository->findBy(
            (ConditionContainer::And())
                ->add(Condition::operator('session_id', '=', $session->getId()))
        );
        
        if (0 === count($userSessions)) {
            return $this->container->get(UserFactory::class)
                ->createLoggedInUserDummy();
        }
        
        if (1 < count($userSessions)) {
            throw new \Exception(sprintf('user session already exists with session_id = %s, SHOULD NOT HAPPEN', $session->getId()));
        }
        
        $userSession = $userSessions[0];
        
        return $this->container->get(UserRepository::class)->findById($userSession->getUserId());
    }

    /**
     * @param UserCredentials $userCredentials
     * @param LoggedInUser $loggedInUser
     * @return bool
     */
    private function verifyUser(UserCredentials $userCredentials, LoggedInUser $loggedInUser): bool
    {
        $incomingPassword = $userCredentials->getPassword();
        $verify           = password_verify($incomingPassword, $loggedInUser->getPassword());

        return $verify;
    }

    /**
     * @param LoggedInUser $loggedInUser
     * @param Session $session
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    private function authorizeDbSession(LoggedInUser $loggedInUser, Session $session): void
    {
        $userSessionRepository = $this->container->get(UserSessionRepository::class);
        $userSessionFactory    = $this->container->get(UserSessionFactory::class);
        $userSession           = $userSessionFactory->createUserSessionPrototype($loggedInUser->getId(), $session->getId());
        $userSessionRepository->save($userSession);
    }

    /**
     * @param LoggedInUser $loggedInUser
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    private function authorizeSession(LoggedInUser $loggedInUser): void
    {
        $userAuthenticationSession = $this->container->get(UserAuthentificationSession::class);
        $userAuthenticationSession->createByLoggedInUser($loggedInUser);
    }
}