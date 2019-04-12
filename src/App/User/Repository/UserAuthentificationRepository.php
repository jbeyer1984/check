<?php

namespace Check\App\User\Repository;

use Check\App\User\Authentification\UserCredentials;
use Check\Globals\Database;

class UserAuthentificationRepository
{
    /**
     * @var Database
     */
    private $db;

    /**
     * UserAuthentificationRepository constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @param UserCredentials $userCredentials
     * @return bool
     * @throws \Exception
     */
    public function existsByUserCredentials(UserCredentials $userCredentials): bool
    {
        $incomingEmail = $userCredentials->getEmail();
        $incomingPassword = $userCredentials->getPassword();
        
        $sql = <<<SQL
SELECT  password
FROM    `user`
WHERE   LOWER(email) = :email
SQL;
        $result = $this->db->query($sql, ['email' => $incomingEmail]);

        $count = count($result);
        if (1 < $count) {
            throw new \Exception('some user with equal email already exists');
        }
        
        if (0 === $count) {
            return false;
        }
        
        $verify = password_verify($incomingPassword, $result[0]['password']);
        
        return $verify;
    }

    /**
     * @param UserCredentials $userCredentials
     * @return array
     */
    public function getFetchedUserData(UserCredentials $userCredentials)
    {
        $incomingEmail = $userCredentials->getEmail();

        $sql = <<<SQL
SELECT  id, name, email, password
FROM    `user`
WHERE   LOWER(email) = :email
SQL;
        $result = $this->db->query($sql, ['email' => $incomingEmail]);
        
        return $result;
    }
}