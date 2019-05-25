<?php

namespace Check\Controller;

use DI\Container;
use DI\ContainerBuilder;

class BaseController
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * BaseController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * @throws \Exception
     */
    private function init()
    {
        $builder = new ContainerBuilder();
        $definitions = require_once(implode(DIRECTORY_SEPARATOR, [ROOT, 'src', 'App', 'di_config.php']));
        $builder->addDefinitions($definitions);
        $this->container = $builder->build();
    }
}