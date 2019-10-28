<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 28.10.2019
 * Time: 23:34
 */

class User
{
    //@type string
    //name of the user from the constructor
    private $username;
    //@type string
    //email form the database
    private $email;
    //@type string
    //User type, specified inside the database
    private $type;
    //@type string
    //Hashed password form the database
    private $password;

    //@type array
    private $permissions = array();

    public function __construct($uid)
    {
        $this->username = $uid;
    }

    public function getUsername()
    {
        return $this->username;
    }
    public function getType()
    {
        return $this->type;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getPassword()
    {
        return $this->password;
    }

}