<?php

namespace Check\App\Controller;

use Check\App\Controller\Action\LoginAction;
use Check\Controller\BaseController;
use Check\Globals\Renderer;
use Check\Globals\Request;

class LoginController extends BaseController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * LoginController constructor.
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    private function init()
    {
        $this->request = $this->container->get(Request::class);
        $this->renderer = $this->container->get(Renderer::class);
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function loginAction()
    {
        $action = new LoginAction($this->container);
        $action->execute();
        
        if ($action->isAuthorized()) {
            $this->request->redirect('/user/start');
        }
        
        $this->renderer->render(['home', 'login.html.php'], $action->getParameter());
    }
}