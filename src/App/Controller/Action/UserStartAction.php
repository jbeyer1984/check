<?php


namespace Check\App\Controller\Action;


use Check\App\User\Factory\UserFactory;
use Check\App\User\Factory\UserRepositoryFactory;
use Check\App\User\Factory\UserSessionFactory;
use Check\Controller\Action\ActionInterface;
use Check\Globals\Database;
use Check\Globals\Request;
use Check\Globals\Session;

class UserStartAction implements ActionInterface
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
     * @var array
     */
    private $parameters = [];

    /**
     * UserStartAction constructor.
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
        if (!$userAuthentificationSession->exists()) {
            $this->request->redirect('/user/login');
        }
        
        $userFactory = new UserFactory();
        $loggedInUser = $userFactory->createLoggedInUserByPersistence($this->session, $this->db);
        
        
        $this->parameters['user'] = $loggedInUser;
    }

    /**
     * @return array
     */
    public function getParameter()
    {
        return $this->parameters;
    }
}