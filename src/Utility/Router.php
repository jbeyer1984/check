<?php


namespace Check\Utility;


use Check\App\Controller\LoginController;
use Check\App\Controller\UserController;
use Check\Factory\GlobalContainerFactory;

class Router
{
    public function route()
    {
        $router = new \Bramus\Router\Router();

//        $router->get('/', function() {
//            echo "hi";
//        });

        $router->match('POST|GET', '/user/login', function() {
            $globalContainerFactory = new GlobalContainerFactory();
            $globalContainer = $globalContainerFactory->createGlobalContainer();
            $controller = new LoginController($globalContainer);
            $controller->loginAction();
//            echo "hi";
        });
        
        $router->match('GET', '/user/start', function() {
            $dump = print_r("hall", true);
            error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** "hall" ***' . PHP_EOL . " = " . $dump . PHP_EOL, 3, '/home/jbeyer/error.log');
            
            $globalContainerFactory = new GlobalContainerFactory();
            $globalContainer = $globalContainerFactory->createGlobalContainer();
            $controller = new UserController($globalContainer);
            $controller->startAction();
        });

        $router->set404(function() {
            echo "not registered path for url";
//            header('HTTP/1.1 404 Not Found');
            // ... do something special here
        });
        
        $router->run();
    }
}