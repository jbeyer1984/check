<?php

namespace Check\App\Controller\Action;

use Check\App\User\Factory\UserFactory;
use Check\App\User\Factory\UserParameterFactory;
use Check\App\User\Factory\UserRepositoryFactory;
use Check\App\User\Factory\UserSessionFactory;
use Check\Controller\Action\ActionInterface;
use Check\Globals\Database;
use Check\Globals\Request;
use Check\Globals\Session;

class LoginAction implements ActionInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Database;
     */
    private $db;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var bool
     */
    private $isUserAuthorized = false;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * WelcomeAction constructor.
     * @param Request $request
     * @param Database $db
     * @param Session $session
     */
    public function __construct(Request $request, Database $db, Session $session)
    {
        $this->request = $request;
        $this->db      = $db;
        $this->session = $session;
    }

    public function execute()
    {
        $userSessionFactory = new UserSessionFactory();
        $userAuthentificationSession = $userSessionFactory->createUserAuthentificationSession($this->session);
        if ($userAuthentificationSession->exists()) {
            $this->isUserAuthorized = true;
            $dump = print_r("000", true);
            error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** "000" ***' . PHP_EOL . " = " . $dump . PHP_EOL, 3, '/home/jbeyer/error.log');
            
            
            return;
        }
        
        // authentificate by db
        
        $userParameterFactory = new UserParameterFactory();
        try {
            $userCredentials = $userParameterFactory->createUserCredentialsByRequest($this->request);   
        } catch (\Exception $e) {
            $this->isUserAuthorized = false;
            
            return;
        }
        $userRepositoryFactory = new UserRepositoryFactory();
        $userAuthentificationRepository = $userRepositoryFactory->createUserAuthentificationRepository($this->db);
        $userIsAuthenticated = $userAuthentificationRepository->existsByUserCredentials($userCredentials);
        
        if ($userIsAuthenticated) {
            $userAuthentificationRepository->getFetchedUserData($userCredentials);
            $userFactory = new UserFactory();
            $loggedInUser = $userFactory->createLoggedInUser($userAuthentificationRepository, $userCredentials);
            $userAuthentificationSession->createByLoggedInUser($loggedInUser);
            // redirect to /user/start
            $this->isUserAuthorized = true;
            
            return;
        }
        
        // not authenticated
        
        // set parameters
        // return
        
        $this->parameters['dear'] = 'ingrid';
    }

    /**
     * @return bool
     */
    public function isUserAuthorized(): bool
    {
        return $this->isUserAuthorized;
    }

    /**
     * @return array
     */
    public function getParameter()
    {
        return $this->parameters;
    }
}