<?php


namespace Check\Globals;


use Exception;

class Session
{
    /**
     * @var string
     */
    private $id;
    
    public function __construct()
    {
        $this->init();
    }
    
    private function init()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            $this->id = session_id();
        }
    }

    private function close()
    {
        session_destroy();
    }

    public function destroy()
    {
        $this->close();
    }

    /**
     * @param array $parameter
     * @return array
     */
    public function query(array $parameter): array
    {
        $result = [];
        foreach ($parameter as $identifier) {
            $result[$identifier] = $_SESSION[$identifier];
        }

        return $result;
    }

    /**
     * @param string $key
     * @return mixed
     * @throws Exception
     */
    public function get(string $key)
    {
        if (!isset($_SESSION[$key])) {
            $this->close();
            throw new Exception(sprintf("session key does not exist, given %s", $key));
        }

//        $this->close();

        return $_SESSION[$key];
    }

    /**
     * @param array $parameter
     * @return bool
     */
    public function existsArrayKeys(array $parameter): bool
    {
        $exists = true;

        foreach ($parameter as $key) {
            if (!isset($_SESSION[$key])) {
                $exists = false;
                break;
            }
        }
        
        return $exists;
    }

    /**
     * @param array $parameter
     * @return bool
     */
    public function existsArraySet(array $parameter): bool
    {
        $exists = true;
        
        foreach ($parameter as $key => $value) {
            if (!isset($_SESSION[$key]) || $value !== $_SESSION[$key]) {
                $exists = false;
                break;
            }
        }
        
        return $exists;
    }

    /**
     * @param string $key
     */
    public function unset(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function addAll(array $parameter): void
    {
        foreach ($parameter as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}