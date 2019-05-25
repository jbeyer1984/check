<?php


namespace Check\App\Controller\Action;


use Check\App\User\Action\Authorize\UserSessionAuthorizationAction;
use Check\App\User\Action\Create\UserCreateAction;
use Check\Controller\Action\ActionInterface;
use DI\Container;

class UserStartAction implements ActionInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var bool
     */
    private $isAuthorized = true;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function execute()
    {
        $userSessionAuthorizationAction = $this->container->get(UserSessionAuthorizationAction::class);
        $userSessionAuthorizationAction->execute();
        if (!$userSessionAuthorizationAction->isAuthorized()) {
            $this->isAuthorized = false;
            
            return;
        }
        
        $userCreationAction = $this->container->get(UserCreateAction::class);
        $userCreationAction->execute();
        
        $this->parameters['user'] = $userCreationAction->getLoggedInUser();
    }

    /**
     * @return array
     */
    public function getParameter()
    {
        return $this->parameters;
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->isAuthorized;
    }
}