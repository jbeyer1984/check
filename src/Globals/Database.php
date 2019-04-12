<?php


namespace Check\Globals;

class Database
{
    /**
     * @var \PDO
     */
    private $connection;
    
    public function __construct()
    {
//        $this->init();
    }

    private function init()
    {
        try {
            $this->connection = new \PDO('mysql:dbname=check;host=127.0.0.1', 'root', 'user20');
        } catch (\Exception $e) {
            $dump = print_r("connection to DB failed", true);
            error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** "connection to DB failed" ***' . PHP_EOL . " = " . $dump . PHP_EOL, 3, '/home/jbeyer/error.log');
            
        }
    }

    private function close()
    {
        $this->connection = null;
    }

    /**
     * @param string $sql
     * @param array $parameter
     * @return array
     */
    public function query(string $sql, array $parameter)
    {
        $this->init();
        $pdo = $this->connection;
        $statement = $pdo->prepare($sql);
        $statement->execute($parameter);
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->close();
        
        return $result;
    }

    /**
     * @param string $sql
     * @param array $parameter
     */
    public function execute(string $sql, array $parameter)
    {
        $this->init();
        $pdo = $this->connection;
        $statement = $pdo->prepare($sql);
        $statement->execute($parameter);
        $this->close();
    }

    /**
     * @return \PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }
}