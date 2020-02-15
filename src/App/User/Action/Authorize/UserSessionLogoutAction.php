<?php


namespace Check\App\User\Action\Authorize;


use Check\App\User\Service\RegisterUserService;
use Check\Controller\Action\ActionInterface;
use DI\Container;

class UserSessionLogoutAction implements ActionInterface
{
    /**
     * @var Container
     */
    private $container;

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
        $isAuthorized = $registerUserService->authorizedUserBySession()->isAuthorized();
        if ($isAuthorized) {
            $registerUserService->logoutUserBySession();
        }
    }

    /**
     * @return array
     */
    public function getParameter()
    {
        return  [];
    }
}