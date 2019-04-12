<?php


namespace Check\App\User\Repository;


use Check\App\User\LoggedInUser;
use Check\Globals\Database;

class UserRepository
{
    /**
     * @var Database
     */
    private $db;

    /**
     * UserRepository constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getFetchedUserSetById(int $id): array
    {
        $sql = <<<SQL
SELECT  id, name, email, password
FROM    user
WHERE   id = :id
SQL;

        $result = $this->db->query($sql, ['id' => $id]);
        
        return $result;
    }
}