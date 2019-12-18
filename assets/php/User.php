<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 28.10.2019
 * Time: 23:34
 */

class User
{
    /**@var PDO
     *Database connection
     */
    private $dbh;

    /**@var string
     *name of the user from the constructor
     */
    private $username;
    /**@var string
     *email form the database
     */
    private $email;
    /**@var string
     *User var, specified inside the database
     */
    private $roleID;
    /**@var string
     *stores the Password in plain text
     */
    private $plainPW;
    /**@var string
     *Hashed password form the database
     */
    private $hashedPW;
    /**
     * @var RBAC
     * Contains the role information of a user
     */
    private $rbac;

    //It will get all user information, if the user exists
    function __construct($uid)
    {
        $this->dbh = Config::dbCon();

        $this->username = $uid;
        if ($this->userExists() === true) {
            $userData = $this->getUserData();
            $this->email = $userData['email'];
            $this->hashedPW = $userData['password'];
            $this->roleID = $userData['role_id'];
            $this->rbac = new RBAC(RBAC::fetchRoleNameFormID($this->roleID));
        }
    }

    /**@return string
     * Returns the hashed password
     *@param string
     * A password from plaintext
     */
    private function hashPW($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }

    /**Returns if the User exists or not
     * @return bool
     *true -> user exists
     *false -> user does not exists
     */
    public function userExists()
    {
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM users WHERE username=:uid");
            $stmt->bindParam(":uid", $this->username);
            $stmt->execute();
            $res = $stmt->fetchAll();
            $exists = null;
            if (count($res) > 0) {
                $exists = true;
            } elseif (count($res) <= 0) {
                $exists = false;
            }
            return $exists;
        } catch (PDOException $e) {
            echo "Getting data from users failed: " . $e->getMessage();
            return;
        }
    }

    /**Returns  all information of an user
     * @return array
     */
    public function getUserData()
    {
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM users WHERE username=:uid");
            $stmt->bindParam(":uid", $this->username);
            $stmt->execute();
            $res = $stmt->fetchAll();
            $userData = array();
            foreach ($res as $re) {
                foreach ($re as $key => $item) {
                    $userData[$key] = $item;
                }
            }
            return $userData;
        } catch (PDOException $e) {
            echo "Getting data from users failed: " . $e->getMessage();
            return;
        }
    }

    /**This function will add a user to the database, if it does not exist.
     * @return bool
     *true -> Adding user was successful
     *false -> User already exist
     */
    public function addUser($email, $plainPassword, $role)
    {
        if ($this->userExists() === false) {
            $hashedPW = $this->hashPW($plainPassword);
            try {
                $stmt = $this->dbh->prepare("INSERT INTO users (username, password, email, role_id) VALUES (:uid, :pw, :email, :u_roleID)");
                $stmt->bindParam(":uid", $this->username);
                $stmt->bindParam(":pw", $hashedPW);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":u_roleID", $role);
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                echo "Writing to settingstype failed: " . $e->getMessage();
            }
        } else {
            return false;
        }
    }

    /**Returns the username, saved inside the object
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**Returns the email of a user, saved inside the object
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**Returns the hashed password, saved inside the object
     * @return string
     */
    public function getHashedPW()
    {
        return $this->hashedPW;
    }

    /**
     * @return RBAC
     */
    public function getRbac()
    {
        return $this->rbac;
    }

    /**
     * @return string
     */
    public function getRoleID()
    {
        return $this->roleID;
    }
}