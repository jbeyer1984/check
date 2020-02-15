<?php


namespace Check\Utility;


use Check\App\Controller\LoginController;
use Check\App\Controller\UserController;

class Router
{
    public function route()
    {
        $router = new \Bramus\Router\Router();

//        $router->get('/', function() {
//            echo "hi";
//        });

        $router->match('POST|GET', '/user/login', function() {
            $controller = new LoginController();
            $controller->loginAction();
//            echo "hi";
        });
        
        $router->match('GET', '/user/start', function() {
            $controller = new UserController();
            $controller->startAction();
        });

        $router->match('GET', '/user/logout', function() {
            $controller = new UserController();
            $controller->logoutAction();
        });

        $router->set404(function() {
            echo "not registered path for url";
//            header('HTTP/1.1 404 Not Found');
            // ... do something special here
        });
        
        $router->run();
    }
}