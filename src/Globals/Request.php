<?php

namespace Check\Globals;

class Request
{
    /**
     * @var GetParameter
     */
    private $getParameter;

    /**
     * @var PostParameter
     */
    private $postParameter;

    /**
     * Request constructor.
     * @param GetParameter $getParameter
     * @param PostParameter $postParameter
     */
    public function __construct(GetParameter $getParameter, PostParameter $postParameter)
    {
        $this->getParameter  = $getParameter;
        $this->postParameter = $postParameter;
    }

    public function redirect($url)
    {
        $host = $_SERVER['HTTP_HOST'];
        $location = 'http://' . $host . $url;
        
        header("Location: " . $location);
        die();
    }

    /**
     * @return GetParameter
     */
    public function getGetParameter()
    {
        return $this->getParameter;
    }

    /**
     * @return PostParameter
     */
    public function getPostParameter()
    {
        return $this->postParameter;
    }
}