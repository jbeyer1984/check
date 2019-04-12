<?php

namespace Check\Controller\Action;

interface ActionInterface
{
    public function execute();

    /**
     * @return array
     */
    public function getParameter();
}