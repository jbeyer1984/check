<?php

namespace Check\Globals;

class PostParameter
{
    private $postParameter = []; 
    
    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->postParameter = $_POST;    
    }

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public function getParameter($key)
    {
        if (!isset($this->postParameter[$key])) {
            throw new \Exception(sprintf('parameter not found in Post Parameter, given %s', $key));
        }
        
        return $this->postParameter[$key];
    }
}