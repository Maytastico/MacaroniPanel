<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 18.11.2019
 * Time: 20:21
 */

class RBAC
{
    /**
     * @var bool
     * Contains the status wheather a role exists or not
     */
    private $exists = false;
    /**@var PDO
     * Saves the database handler
     */
    private $dbh;
    /**@var array
     * Saves all permissions of a role as a number
     */
    private $permissionIDs = array();
    /**
     * @var array
     * Saves the permissions of a role as the name
     */
    private $permissionsAsName = array();
    /**@var string
     * Saves the name of the role
     */
    private $roleName = "";
    /**@var integer
     * Saves the ID of a role
     */
    private $roleID = -1;

    /**@param string
     *The first parameter is the Name of the role.
     *Futhermore requiers this class a Databasehandler
     *So it fetches these information from the Config class
     */
    function __construct($name)
    {
        $this->roleName = $name;
        $this->dbh = Config::dbCon();

        if ($this->roleExists()) {
            $this->exists = true;
            $this->roleID = $this->fetchRoleIDFromName();
            $this->permissionIDs = $this->fetchPermissions();
        }

    }

    /**
     * @return bool|null
     * true = Role Exists
     * false = Role does not exist
     * null = Something strange is going on
     */
    public function roleExists()
    {
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM role WHERE id=:roleid OR name=:roleName");
            $stmt->bindParam(":roleid", $this->roleID);
            $stmt->bindParam(":roleName", $this->roleName);
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
            echo "Fetching data from role failed: " . $e->getMessage();
        }
    }
    /**
     * @return bool
     * true = if everything was successful
     * false = if the role exists or there were no permissions set
     */
    public function createRole()
    {
        try {
            if (count($this->permissionIDs) >= 1) {
                if (!$this->roleExists()) {
                    $stmt = $this->dbh->prepare("INSERT INTO role(name) VALUES (:roleName);");
                    $stmt->bindParam(":roleName", $this->roleName);
                    $stmt->execute();
                    $this->roleID = $this->fetchRoleIDFromName();
                    $this->addRoleAndPermissionRelations();
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Creating new role failed " . $e->getMessage();
        }
    }

    /**
     * @return bool
     * Removes the with the corresponding permissions
     */
    public function removeRole(){
        try {
            $this->removeRoleAndPermissionRealations();
            $stmt = $this->dbh->prepare("DELETE FROM role WHERE id=:roleID");
            $stmt->bindParam(":roleID", $this->roleID);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Deleting $this->roleName role relations failed " . $e->getMessage();
        }
    }
    /**
     * Gets the role id and permission ids from the attributes and adds them into the bridge table.
     * The bridge table contains  role and the corresponding permission.
     */
    private function addRoleAndPermissionRelations()
    {
        try {
            $stmt = $this->dbh->prepare("INSERT INTO role_has_permission(role_id, permission_id) VALUES (:roleID, :permissionID)");
            $stmt->bindParam(":roleID", $this->roleID);
            foreach ($this->permissionIDs as $permissionID){
                $stmt->bindParam(":permissionID", $permissionID);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            echo "Creating role-permission relations failed " . $e->getMessage();
        }
    }

    /**
     * Removes the relation inside the bridge table
     */
    private function removeRoleAndPermissionRealations(){
        try {
            $stmt = $this->dbh->prepare("DELETE FROM role_has_permission WHERE role_id=:roleID");
            $stmt->bindParam(":roleID", $this->roleID);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Deleting role-permission relations failed " . $e->getMessage();
        }
    }

    /**
     * @return integer
     * Return the id from a Role Name
     * Multiple role entries with the same name will be ignored.
     */
    private function fetchRoleIDFromName()
    {
        try {
            if ($this->roleExists()) {
                $stmt = $this->dbh->prepare("SELECT id FROM role WHERE name=:roleName");
                $stmt->bindParam("roleName", $this->roleName);
                $stmt->execute();
                $queryResults = $stmt->fetchAll();
                $roleID = $queryResults[0]["id"];
                settype($roleID, "Integer");
                return $roleID;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Getting Role ID failed: " . $e;
        }
    }

    private function fetchPermissions(){
        try {
            if ($this->roleExists()) {
                $stmt = $this->dbh->prepare("SELECT  permission_id FROM role INNER JOIN  role_has_permission ON role.id=role_has_permission.role_id WHERE role_id=:roleID");
                $stmt->bindParam("roleID", $this->roleID);
                $stmt->execute();
                $queryResults = $stmt->fetchAll();
                $permissions = array();
                $index = 0;
                foreach ($queryResults as $key=>$permission){
                    $permissions[$index] = $permission[0];
                    $index++;
                }
                return $permissions;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Fetching permission ID failed: " . $e;
        }
    }

    private function fetchPermissionNames(){
        try {
            if ($this->roleExists()) {
                $stmt = $this->dbh->prepare("SELECT  permission_id FROM role INNER JOIN  role_has_permission ON role.id=role_has_permission.role_id WHERE role_id=:roleID");
                $stmt->bindParam("roleID", $this->roleID);
                $stmt->execute();
                $queryResults = $stmt->fetchAll();
                $permissions = array();
                $index = 0;
                foreach ($queryResults as $key=>$permission){
                    $permissions[$index] = $permission[0];
                    $index++;
                }
                return $permissions;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Fetching permission ID failed: " . $e;
        }
    }
    /**
     * Updates the role ID inside the object
     */
    private function updateRoleID(){
        if($this->roleExists()){
            $this->roleID = $this->fetchRoleIDFromName();
        }else{
            $this->roleID = -1;
        }
    }

    /**
     * @param $id
     * The permissionID that should be added to the role
     */
    public function addPermission($id)
    {
        if ($this->exists === false){
            $sizeofPermission = count($this->permissionIDs);
            $this->permissionIDs[$sizeofPermission] = $id;
        }
    }

    /**
     * @param $roleName
     * Sets a another Name inside the object, so the object can be
     * reused
     */
    private function setRoleName($roleName)
    {
        $this->roleName = $roleName;
        $this->updateRoleID();
    }

    /**
     * @return string
     * Returns the name of the role
     */
    public function getRoleName()
    {
        return $this->roleName;
    }

    /**
     * @return string
     */
    public function getRoleID()
    {
        //settype($this->roleID, "Integer");
        return $this->roleID;
    }

    /**
     * @return array
     * Returns the permissions of the user
     */
    public function getPermissionIDs()
    {
        return $this->permissionIDs;
    }
}
