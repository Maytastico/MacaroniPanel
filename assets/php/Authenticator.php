<?php


class Authenticator extends User
{
    /**
     * @var array
     * Contains the data of the user for authenticating its previleges.The data is stored on the server it is
     * referred to a php-session cookie that is stored inside browser of the user.
     */
    private $sessionData = [];

    /**
     * Authenticator constructor.
     * @param $uid
     * The constructor constructs the user object and fetches the data of the php-session
     */
    public function __construct($uid)
    {
        parent::__construct($uid);
        //Initializes a php session
        self::initSession();
        //If a session is active and available, it will get all the user data from the session container.
        if (session_status() == PHP_SESSION_ACTIVE)
            $this->fetchSessionData();
    }

    /**
     * If a session does not exist it will create one
     */
    static public function initSession(){
        //If a session does not exist it will start a session
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if(session_status() == PHP_SESSION_DISABLED){
            echo "No sessions module not available. Maybe disabled?";
            error_log("Error while initializing php-sessions. Maybe disabled?");
        }
    }
    /**
     * @return bool
     * The plain password has to be set first so the function can get the plain password from the object and is able to
     * to check whether the password is right. This function is used inside the login script to verify the password.
     */
    public function checkPassword()
    {
        $plainPassword = $this->plainPW;
        $hashedPassword = $this->getHashedPW();
        return password_verify($plainPassword, $hashedPassword);
    }

    /**
     * @param $permissionAttribute
     * @return bool
     * This function needs a string that contains a permission attribute like "usermanager.addUser" that should be checked.
     * If it finds the permission attribute inside the permission array of the RBAC object, it will returns true.
     * This function is used in scripts that have to check the privileges of the user to grand access.
     * true: permission was found
     * false: user has no permission
     */
    public function hasPermission($permissionAttribute)
    {
        if ($this->userExists() === true) {
            $permissionAttribute = trim($permissionAttribute);
            $u_permissions = $this->getRbac()->getPermissionsAsName();
            foreach ($u_permissions as $u_permission) {
                if ($u_permission === $permissionAttribute) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @return bool
     * Checks whether the user exists and whether the session isn't deprecated
     * This is used to verify access inside a script.
     * true: session exists
     * false: user does not exist or session is deprecated
     */
    public function verifySession()
    {
        if ($this->userExists() === true) {
            if ($this->checkSessionID() === true) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     * Compares the sessionID data form the database and the sessionID form the php-session
     * true: The session is available
     * false: The session is deprecated
     */
    public function checkSessionID()
    {
        //Gets the sessionID of the user from the database
        $u_sessionID = $this->getSessionID();
        //Gets the sessionID from the php-session
        $s_sessionID = $this->sessionData['u_sessionID'];

        //Checks whether one of these variables is not empty
        if (!empty($s_sessionID) && !empty($u_sessionID)) {
            //Check whether these variable is not NULL
            if ($s_sessionID !== NULL && $u_sessionID !== NULL) {
                //Checks whether the sessionID of the php-session and the sessionID of the user
                //is the same
                if ($s_sessionID === $u_sessionID) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * @return bool|string
     * This gets the username from the session container.
     * This will be used to construct the "User" or "Authenticator" Object inside a script,
     * because these objects need a username to be constructed
     * bool: is returned when a session wasn't created
     * string: contains the username from the session
     */
    static function fetchSessionUserName()
    {
        self::initSession();
        $s_username = false;
        if (isset($_SESSION["u_name"]))
            $s_username = $_SESSION["u_name"];
        return $s_username;
    }

    /**
     *  Gets the data from the php-session.
     *  This is used to get all the session data from the container.
     *  It is not necessary at all.
     */
    private function fetchSessionData()
    {
        foreach ($_SESSION as $key => $sessionEntry) {
            $this->sessionData[$key] = $sessionEntry;
        }
    }

    /**
     * This function writes some basic information of the user into the php-session.
     * This function will be executed by the user, if  logs into its account.
     */
    public function writeSessionData()
    {
        //Writes the server time into the session for an auto logout feature
        //todo auto logout feature
        $_SESSION["created"] = time();
        //Writes the username to the session for identify what user is meant
        $_SESSION["u_name"] = $this->getUsername();
        //Writes a random number that is to authenticate the user
        $this->updateSessionID();
        $_SESSION["u_sessionID"] = $this->getSessionID();
    }

    /**
     * Overrides the php-session data, destroys the php-session and writes
     * another sessionID into the database, so the old php-session is deprecated.
     * It is used to log a user out of its account
     */
    public function resetSession()
    {
        $_SESSION = array();
        session_destroy();
        $this->updateSessionID();
    }
}