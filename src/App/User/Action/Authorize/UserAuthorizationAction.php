<?php


namespace Check\App\User\Action\Authorize;


use Check\App\User\Factory\UserParameterFactory;
use Check\App\User\Service\RegisterUserService;
use Check\Controller\Action\ActionInterface;
use Check\Controller\Action\AuthorizationInterface;
use DI\Container;
use Exception;

class UserAuthorizationAction implements ActionInterface, AuthorizationInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var bool
     */
    private $isAuthorized = false;

    /**
     * UserRepositoryAuthorizationAction constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function execute()
    {
        $userParameterFactory = $this->container->get(UserParameterFactory::class);
        try {
            $userCredentials = $userParameterFactory->createUserCredentialsByRequest();
            $registerUserService = $this->container->get(RegisterUserService::class);
            
            $loggedInUser = $registerUserService->authorizedUserByCredentials($userCredentials);
            if (!$loggedInUser->isAuthorized()) {
                return;
            }
            
            $registerUserService->authorizeSessionByLoggedInUser($loggedInUser);
            
            $this->isAuthorized = $loggedInUser->isAuthorized();
        } catch (Exception $e) {
            // logging
            $this->isAuthorized = false;
        }
    }

    /**
     * @return array
     */
    public function getParameter()
    {
        return [];
    }

    /**
     * @return bool
     */
    public function isAuthorized()
    {
        return $this->isAuthorized;
    }
}