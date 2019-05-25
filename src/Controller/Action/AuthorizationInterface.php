<?php


namespace Check\Controller\Action;


interface AuthorizationInterface
{
    /**
     * @return bool
     */
    public function isAuthorized();
}