<?php


namespace Check\App\User\Factory;

use Check\App\User\Authentification\UserCredentials;
use Check\Globals\Request;
use DI\Container;

class UserParameterFactory
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var Request
     */
    private $request;

    /**
     * UserParameterFactory constructor.
     * @param Container $container
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->init();
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    private function init()
    {
        $this->request = $this->container->get(Request::class);
    }


    /**
     * @return UserCredentials
     * @throws \Exception
     */
    public function createUserCredentialsByRequest(): UserCredentials
    {
        return new UserCredentials(
//            $request->getGetParameter()->getParameter('email'),
            $this->request->getPostParameter()->getParameter('email'),
//            $request->getGetParameter()->getParameter('password')
            $this->request->getPostParameter()->getParameter('password')
        );
    }
}