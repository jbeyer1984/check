<?php

namespace Check\App\Controller\Action;

use Check\App\User\Action\Authorize\UserAuthorizationAction;
use Check\App\User\Action\Authorize\UserSessionAuthorizationAction;
use Check\Controller\Action\ActionInterface;
use DI\Container;

class LoginAction implements ActionInterface
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
     * @var array
     */
    private $parameters = [];

    /**
     * WelcomeAction constructor.
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
        $userSessionAuthorizationAction = $this->container->get(UserSessionAuthorizationAction::class);
        $userSessionAuthorizationAction->execute();
        
        if ($userSessionAuthorizationAction->isAuthorized()) {
            $this->isAuthorized = true;
            
            return;
        }
        
        $userRepositoryAuthorizationAction = $this->container->get(UserAuthorizationAction::class);
        $userRepositoryAuthorizationAction->execute();
        
        if (!$userRepositoryAuthorizationAction->isAuthorized()) {
            $this->isAuthorized = false;
            
            return;
        }
        
        $this->isAuthorized = true;
        $this->parameters['dear'] = 'ingrid';
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->isAuthorized;
    }

    /**
     * @return array
     */
    public function getParameter()
    {
        return $this->parameters;
    }
}