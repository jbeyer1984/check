<?php


namespace Check\App\User\Action\Authorize;


use Check\App\User\Service\RegisterUserService;
use Check\Controller\Action\ActionInterface;
use Check\Controller\Action\AuthorizationInterface;
use DI\Container;

class UserSessionAuthorizationAction implements ActionInterface, AuthorizationInterface
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
     * UserAuthenticationAction constructor.
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
        $this->isAuthorized = $registerUserService->authorizedUserBySession()->isAuthorized();
    }

    /**
     * @return array
     */
    public function getParameter()
    {
        return  [];
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->isAuthorized;
    }
}