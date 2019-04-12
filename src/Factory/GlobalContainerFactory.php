<?php

namespace Check\Factory;

use Check\Globals\Database;
use Check\Globals\GetParameter;
use Check\Globals\GlobalContainer;
use Check\Globals\PostParameter;
use Check\Globals\Request;
use Check\Globals\Session;
use Check\Globals\Renderer;

class GlobalContainerFactory
{
    /**
     * @return GlobalContainer
     */
    public function createGlobalContainer()
    {
        return new GlobalContainer(
            $this->createRequest(),
            $this->createRenderer(),
            $this->createDb(),
            $this->createSession()
        );
    }

    /**
     * @return Request
     */
    private function createRequest()
    {
        return new Request(
            $this->createGetParameter(),
            $this->createPostParameter()
        );
    }

    /**
     * @return GetParameter
     */
    private function createGetParameter()
    {
        return new GetParameter();
    }

    /**
     * @return PostParameter
     */
    private function createPostParameter()
    {
        return new PostParameter();
    }

    /**
     * @return Renderer
     */
    private function createRenderer()
    {
        return new Renderer();
    }

    /**
     * @return Database
     */
    private function createDb()
    {
        return new Database();
    }

    private function createSession()
    {
        return new Session();
    }

}