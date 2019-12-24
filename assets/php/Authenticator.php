<?php


class Authenticator extends User
{
    /**
     * @var array
     * Contains the data of the user for authenticating its previleges.The data is stored on the server it is
     * refered to a php-session cookie that is stored inside browser of the user.
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
        $this->fetchSessionData();
    }

    /**
     * @param $permissionAttribute
     * @return bool
     * This function needs a string that contains a permission attribute like "usermanager.addUser" that should be checked.
     * If it finds the permission attribute inside the permission array of the RBAC object it returns true.
     * This function is used in scripts that have to check the priviliges of the user to grand access.
     * true: permission was found
     * false: user has no permission
     */
    public function hasPermission($permissionAttribute)
    {
        $permissionAttribute = trim($permissionAttribute);
        $u_permissions = $this->getRbac()->getPermissionsAsName();
        foreach ($u_permissions as $u_permission) {
            if ($u_permission === $permissionAttribute) {
                return true;
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
     * @return string
     * This gets the username from the Session.
     * This will be used to construct the "User" or "Authenticator" Object inside a script,
     * because these objects need a username to be constructed
     */
    static function fetchSessionUserName()
    {
        session_start();
        $s_username = $_SESSION["u_name"];
        session_write_close();
        return $s_username;
    }

    /**
     *  Gets the data from the php-session
     */
    private function fetchSessionData()
    {
        session_start();
        foreach ($_SESSION as $key => $sessionEntry) {
            $this->sessionData[$key] = $sessionEntry;
        }
        session_write_close();
    }

    /**
     * This function writes some basic information of the user into the php-session.
     * This function can be executed of a user logs into its account.
     */
    public function writeSessionData()
    {
        //Opens a new session or continues a session
        //This function is necessary to access the session data
        session_start();
        //Writes the server time into the session for an auto logout feature
        $_SESSION["created"] = time();
        //Writes the username to the session for identify what user is meant
        $_SESSION["u_name"] = $this->getUsername();
        //Writes a random number that is to authenticate the user
        $this->updateSessionID();
        $_SESSION["u_sessionID"] = $this->getSessionID();
        session_write_close();
    }


}