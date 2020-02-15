<?php


namespace Check\App\Controller;


use Check\App\Controller\Action\UserStartAction;
use Check\App\User\Action\Authorize\UserSessionLogoutAction;
use Check\Controller\BaseController;
use Check\Globals\Renderer;
use Check\Globals\Request;

class UserController extends BaseController
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var Request
     */
    private $request;

    /**
     * UserController constructor.
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
        $this->renderer = $this->container->get(Renderer::class);
        $this->request = $this->container->get(Request::class);
    }


    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function startAction()
    {
        $action = new UserStartAction($this->container);
        $action->execute();
        if (!$action->isAuthorized()) {
            $this->request->redirect('/user/login');
        }

        $this->renderer->render(['home', 'start.html.php'], $action->getParameter());
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function logoutAction()
    {
        $action = new UserStartAction($this->container);
        $action->execute();
        if (!$action->isAuthorized()) {
            $this->request->redirect('/user/login');
        }
        $action = new UserSessionLogoutAction($this->container);
        $action->execute();

        $this->request->redirect('/user/login');
    }
}