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
    /**
     * @var string
     *The role id is in relation to the role table inside the database.
     *This is to authorize actions of the user.
     */
    private $roleID;
    /**@var string
     *Stores the Password in plain text.
     * This is for adding a new user to the database
     */
    protected $plainPW;
    /**@var string
     *Hashed password form the database
     */
    private $hashedPW;
    /**
     * @var string
     * Contains the Session ID that is generated after logging in into the dashboard.
     * It prevents that two clients can login into the dashboard at the same time.
     * So the Cookie can't be stolen by a malicious software.
     */
    private $sessionID;
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
            $this->sessionID = $userData['sessionID'];
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

    /**
     * Returns  all information of an user
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

    /**
     * It gets a hash and writes it as the sessionID to the database.
     */
    public function updateSessionID(){
        try{
            $sessionID = $this->generateSessionID();
            $stmt = $this->dbh->prepare("UPDATE users SET sessionID=:sessionID where username=:userID");
            $stmt->bindParam(":sessionID", $sessionID);
            $stmt->bindParam(":userID", $this->username);
            $stmt->execute();
            $this->sessionID = $sessionID;

        } catch (PDOException $e){
            echo "Updating the sessionID of $this->username failed: " . $e->getMessage();
        }
    }

    /**
     * @return string
     * This function generates a hash that should be as random as it can be.
     * It takes a random number form -2,147,483,648 to 2,147,483,647 , the username, the email of the user and generates
     * a md5 hash. This hash will be hashed few times, to ensure a secure sessionID that can't be bruteforced.
     * This hash will be saved inside the php-session and database to authenticate a user.
     */
    private function generateSessionID(){
        $randomIteration = rand(1, 10);
        $randomNumber = rand(-0x7FFFFFFF, 0x7FFFFFFF);
        $lastIteration = md5($randomNumber . $this->username . $this->email);
        $newSessionID = "";
        for($i=0; $i<=$randomIteration; $i++){
            $newSessionID = md5($lastIteration);
            $lastIteration = $newSessionID;
        }
        return $newSessionID;
    }
    /**
     * @param $newUsername
     * @return bool
     * $newUsername should contain the new username of the user
     * Changes the username of a user. This method is used in scripts that change properties of a user.
     * true: The username was changed successful.
     * false: The user does not exist or something went wrong during the changing process.
     */
    public function updateUsername($newUsername){
        $newUser = new User($newUsername);
        if ($newUser->userExists() === false) {
            try {
                $this->username = $newUsername;
                $stmt = $this->dbh->prepare("UPDATE users set username=:u_name where :role_id");
                $stmt->bindParam(":u_name", $this->username);
                $stmt->bindParam(":role_id", $this->roleID);
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                echo "Updating username failed: " . $e->getMessage();
                exit();
            }
        } else {
            return false;
        }
    }

    public function updatePassword($oldPassword, $newPassword){
        if(password_verify($this->hashedPW, $oldPassword) === true){
            try {
                $newHashedPassword = $this->hashPW($newPassword);
                $this->hashedPW = $newHashedPassword;
                $stmt = $this->dbh->prepare("UPDATE users set password=:u_pw where :role_id");
                $stmt->bindParam(":u_pw", $this->hashedPW);
                $stmt->bindParam(":role_id", $this->roleID);
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                echo "Updating username failed: " . $e->getMessage();
                exit();
            }
        }else{
            return false;
        }
    }

    /**
     * @param $newEmail
     * @return bool
     * $newEmail should contain the new email address of the user
     * Changes the email of a user. This method is used in scripts that change properties of a user.
     * true: The email was changed successful
     * false: The user does not exist or something went wrong during the changing process
     */
    public function updateEmail($newEmail){
        if ($this->userExists() === true) {
            try {
                $this->email = $newEmail;
                $stmt = $this->dbh->prepare("UPDATE users set email=:u_email where :role_id");
                $stmt->bindParam(":u_email", $this->email);
                $stmt->bindParam(":role_id", $this->roleID);
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                echo "Updating email failed: " . $e->getMessage();
                exit();
            }
        } else {
            return false;
        }
    }
    /**
     * @return string
     * Returns the username, saved inside the object
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     * Returns the email of a user, saved inside the object
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     * Returns the hashed password, saved inside the object
     */
    public function getHashedPW()
    {
        return $this->hashedPW;
    }

    /**
     * @return RBAC
     * Returns the rbac object. It contains the permission data of the user
     */
    public function getRbac()
    {
        return $this->rbac;
    }

    /**
     * @return string
     * Returns the role id stored inside the users table.
     */
    public function getRoleID()
    {
        return $this->roleID;
    }

    /**
     * @return string
     * Returns the SessionID to authenticate the user
     */
    public function getSessionID()
    {
        return $this->sessionID;
    }

    /**
     * @param string $plainPW
     */
    public function setPlainPW($plainPW)
    {
        $this->plainPW = $plainPW;
    }
}