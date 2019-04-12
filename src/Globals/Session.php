<?php


namespace Check\Globals;


class Session
{
    public function __construct()
    {
        $dump = print_r("session constr", true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** "session constr" ***' . PHP_EOL . " = " . $dump . PHP_EOL, 3, '/home/jbeyer/error.log');
        
        $this->init();
    }
    
    private function init()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function close()
    {
        session_destroy();
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
     * @throws \Exception
     */
    public function get(string $key)
    {
        if (!isset($_SESSION[$key])) {
            $this->close();
            throw new \Exception(sprintf("session key does not exist, given %s", $key));
        }

        $this->close();

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
}