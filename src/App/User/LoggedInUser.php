<?php


namespace Check\App\User;


class LoggedInUser
{
    private $id;
    
    private $name;
    
    private $email;
    
    private $password;

    /**
     * LoggedInUser constructor.
     * @param $id
     * @param $name
     * @param $email
     * @param $password
     */
    public function __construct($id, $name, $email, $password)
    {
        $this->id       = $id;
        $this->name     = $name;
        $this->email    = $email;
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }
}