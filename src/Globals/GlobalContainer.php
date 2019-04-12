<?php


namespace Check\Globals;


use Check\Globals\Renderer;

class GlobalContainer
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var Database
     */
    private $db;

    /**
     * @var Session
     */
    private $session;

    /**
     * GlobalContainer constructor.
     * @param Request $request
     * @param Renderer $renderer
     * @param Database $db
     * @param Session $session
     */
    public function __construct(Request $request, Renderer $renderer, Database $db, Session $session)
    {
        $this->request  = $request;
        $this->renderer = $renderer;
        $this->db       = $db;
        $this->session  = $session;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Renderer
     */
    public function getRenderer(): Renderer
    {
        return $this->renderer;
    }

    /**
     * @return Database
     */
    public function getDb(): Database
    {
        return $this->db;
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }
}