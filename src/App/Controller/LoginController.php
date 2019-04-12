<?php

namespace Check\App\Controller;

use Check\App\Controller\Action\LoginAction;
use Check\Controller\BaseController;

class LoginController extends BaseController
{
    public function loginAction()
    {
        $action = new LoginAction($this->getRequest(), $this->getDb(), $this->getSession());
        $action->execute();
        
        if ($action->isUserAuthorized()) {
            $this->getRequest()->redirect('/user/start');
        }
        
        $this->getRenderer()->render(['home', 'login.html.php'], $action->getParameter());
    }
}