<?php

namespace Check\Globals;

class GetParameter
{
    private $getParameter = [];

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->getParameter = $_GET;
    }

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public function getParameter($key)
    {
        if (!isset($this->getParameter[$key])) {
            throw new \Exception(sprintf('parameter not found in Post Parameter, given %s', $key));
        }
        
        return $this->getParameter[$key];
    }
}