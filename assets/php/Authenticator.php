<?php


class Authenticator extends User
{
    public function __construct($uid)
    {
        parent::__construct($uid);
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
}