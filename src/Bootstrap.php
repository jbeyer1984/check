<?php

namespace Check;

use Check\Utility\Router;

class Bootstrap
{
    public function __construct()
    {
        $this->init();
    }
    
    private function init()
    {
        define('ROOT', dirname(__DIR__));
        define('PUBLIC', implode(DIRECTORY_SEPARATOR, [ROOT,  'public']));
        define('VIEW', implode(DIRECTORY_SEPARATOR, [ROOT,  'public', 'view']));

        $router = new Router();
        $router->route();
    }
}