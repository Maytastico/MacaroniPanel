<?php


class Authenticator extends User
{
    private $sessionData = [];

    public function __construct($uid)
    {
        parent::__construct($uid);
        $this->fetchSessionData();
    }

    public function hasPermission($permissionAttribute){
        $permissionAttribute = trim($permissionAttribute);
        $u_permissions = $this->getRbac()->getPermissionsAsName();
        foreach ($u_permissions as $u_permission){
            if($u_permission === $permissionAttribute){
                return true;
            }
        }
        return false;
    }

    public function checkSession(){
        if($this->userExists()===true){
            if($this->verifySessionID() === true){
                return true;
            }
        }
        return false;
    }

    public function verifySessionID()
    {
        $u_sessionID = $this->getSessionID();
        $s_sessionID = $this->sessionData['u_sessionID'];
        if (!empty($s_sessionID) || !empty($u_sessionID)){
            if ($s_sessionID !== NULL || $u_sessionID !== NULL) {
                if ($s_sessionID === $u_sessionID) {
                    return true;
                }
            }
        }
        return false;
    }

    static function fetchSessionUserName(){
        session_start();
        $s_username = $_SESSION["u_name"];
        session_write_close();
        return $s_username;
    }
    private function fetchSessionData(){
        session_start();
        foreach ($_SESSION as $key=>$sessionEntry){
            $this->sessionData[$key] = $sessionEntry;
        }
        session_write_close();
    }

    public function writeSessionData(){
        session_start();
        $_SESSION["created"] = time();
        $_SESSION["u_name"] = $this->getUsername();
        $_SESSION["u_sessionID"] = $this->getSessionID();
        session_write_close();
    }
}