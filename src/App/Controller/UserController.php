<?php


namespace Check\App\Controller;


use Check\App\Controller\Action\UserStartAction;
use Check\Controller\BaseController;

class UserController extends BaseController
{
    public function startAction()
    {
        $action = new UserStartAction($this->getRequest(), $this->getDb(), $this->getSession());
        $action->execute();

        $this->getRenderer()->render(['home', 'start.html.php'], $action->getParameter());
    }
}