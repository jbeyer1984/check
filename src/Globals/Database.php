<?php


namespace Check\Globals;

use Check\Persistence\PersistenceInterface;

class Database implements PersistenceInterface
{
    /**
     * @var \PDO
     */
    private $connection;
    
    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        try {
            $this->connection = new \PDO('mysql:dbname=check;host=127.0.0.1', 'root', 'user20');
        } catch (\Exception $e) {
            /** @TODO jbeyer log Exception */
        }
    }

//    private function close()
//    {
//        $this->connection = null;
//    }

    /**
     * @param string $sql
     * @param array $parameter
     * @return array
     */
    public function query(string $sql, array $parameter)
    {
//        $this->init();
        $pdo = $this->connection;
        $statement = $pdo->prepare($sql);
        $statement->execute($parameter);
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
//        $this->close();
        
        return $result;
    }

    /**
     * @param string $sql
     * @param array $parameter
     */
    public function execute(string $sql, array $parameter): void
    {
        $this->init();
        $pdo = $this->connection;
        $statement = $pdo->prepare($sql);
        $statement->execute($parameter);
//        $this->close();
    }

    /**
     * @return int
     */
    public function getLastInsertedId(): int
    {
//        $this->init();
        $pdo = $this->connection;
        $sql = 'SELECT LAST_INSERT_ID() AS id';
        $statement = $pdo->prepare($sql);
        $statement->execute([]);
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        
        $id = $result[0]['id'];
//        $this->close();
        
        return $id;
    }

//    /**
//     * @return \PDO
//     */
//    public function getConnection()
//    {
//        return $this->connection;
//    }
}