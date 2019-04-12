<?php


namespace Check\App\User\Factory;

use Check\App\User\Authentification\UserCredentials;
use Check\Globals\Request;

class UserParameterFactory
{
    /**
     * @param Request $request
     * @return UserCredentials
     * @throws \Exception
     */
    public function createUserCredentialsByRequest(Request $request): UserCredentials
    {
        return new UserCredentials(
//            $request->getGetParameter()->getParameter('email'),
            $request->getPostParameter()->getParameter('email'),
//            $request->getGetParameter()->getParameter('password')
            $request->getPostParameter()->getParameter('password')
        );
    }
}