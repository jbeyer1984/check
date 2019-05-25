<?php


namespace Check\App\User\Action\Create;


use Check\App\User\LoggedInUser;
use Check\App\User\Service\RegisterUserService;
use Check\Controller\Action\ActionInterface;
use DI\Container;

class UserCreateAction implements ActionInterface
{
    /**
     * @var Container
     */
    private $container;
    /**
     * @var LoggedInUser
     */
    private $loggedInUser;

    /**
     * UserCreationAction constructor.
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
        $registerUserService = $this->container->get(RegisterUserService::class);
        $this->loggedInUser = $registerUserService->getAuthorizedUserBySession();
    }

    /**
     * @return array
     */
    public function getParameter()
    {
        return [];
    }

    /**
     * @return LoggedInUser
     */
    public function getLoggedInUser(): LoggedInUser
    {
        return $this->loggedInUser;
    }
    
    
    
    
}