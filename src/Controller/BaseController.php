<?php

namespace Check\Controller;

use Check\App\User\Session\UserAuthentificationSession;
use Check\Globals\Database;
use Check\Globals\GlobalContainer;
use Check\Globals\Request;
use Check\Globals\Session;
use Check\Globals\Renderer;
use DI\Container;
use DI\ContainerBuilder;

class BaseController
{
    /**
     * @var GlobalContainer
     */
    private $globalContainer;

    /**
     * BaseController constructor.
     * @param GlobalContainer $globalContainer
     */
    public function __construct(GlobalContainer $globalContainer)
    {
        $this->globalContainer = $globalContainer;
        $this->init();
    }

    private function init()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions(implode(DIRECTORY_SEPARATOR, [ROOT, 'src', 'di_config.php']));
        
//        $container = new Container();
//        $userAthentificationSession = $container->get(UserAuthentificationSession::class); 
//        $userAthentificationSession = $container->get(UserAuthentificationSession::class); 
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->globalContainer->getRequest();
    }

    /**
     * @return Renderer
     */
    public function getRenderer(): Renderer
    {
        return $this->globalContainer->getRenderer();
    }

    /**
     * @return Database
     */
    public function getDb(): Database
    {
        return $this->globalContainer->getDb();
    }

//    /**
//     * @return Session
//     */
//    public function getSession(): Session
//    {
//        return $this->globalContainer->getSession();
//    }
}