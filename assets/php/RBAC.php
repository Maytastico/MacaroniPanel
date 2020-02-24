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

        if ($this->roleExists()) {
            $this->exists = true;
            $this->roleID = RBAC::fetchRoleIDFromName($name);
            $this->permissionIDs = $this->fetchPermissions();
            $this->permissionsAsName = $this->evaluatePermissionNames();
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
            $stmt = Config::dbCon()->prepare("SELECT * FROM role WHERE id=:roleid OR name=:roleName");
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
    public function addRole()
    {
        try {
            if (count($this->permissionIDs) >= 1) {
                if (!$this->roleExists()) {
                    $stmt = Config::dbCon()->prepare("INSERT INTO role(name) VALUES (:roleName);");
                    $stmt->bindParam(":roleName", $this->roleName);
                    $stmt->execute();
                    $this->roleID = self::fetchRoleIDFromName($this->roleName);
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
            $stmt = Config::dbCon()->prepare("DELETE FROM role WHERE id=:roleID");
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
            $stmt = Config::dbCon()->prepare("INSERT INTO role_has_permission(role_id, permission_id) VALUES (:roleID, :permissionID)");
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
            $stmt = Config::dbCon()->prepare("DELETE FROM role_has_permission WHERE role_id=:roleID");
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
    static public function fetchRoleIDFromName($roleName)
    {
        try {
            $stmt = Config::dbCon()->prepare("SELECT id FROM role WHERE name=:roleName");
            $stmt->bindParam("roleName", $roleName);
            $stmt->execute();
            $queryResults = $stmt->fetchAll();
            $roleID = $queryResults[0]["id"];
            settype($roleID, "Integer");
            return $roleID;
        } catch (PDOException $e) {
            echo "Getting Role ID failed: " . $e->getMessage();
        }
    }

    /**
     * @param $id
     * Gets the role id
     * @return bool|string
     * false: Role id was not found
     * string: Returns the Role Name
     */
    static public function fetchRoleNameFormID($id)
    {
        try {
            $stmt = Config::dbCon()->prepare("SELECT name FROM role WHERE id=:roleID");
            $stmt->bindParam("roleID", $id);
            $stmt->execute();
            $queryResults = $stmt->fetchAll();
            //Error catching if the id does not exist
            if(count($queryResults)<=0){
                return false;
            }
            $roleName = $queryResults[0]["name"];
            settype($roleName, "String");
            return $roleName;
        } catch (PDOException $e) {
            echo "Getting Role Name failed: " . $e->getMessage();
        }
    }

    static public function fetchPermissionID($permissionAttribute){
        try {
            $stmt = Config::dbCon()->prepare("SELECT id FROM permissions WHERE name=:permissionAttribute");
            $stmt->bindParam("permissionAttribute", $permissionAttribute);
            $stmt->execute();
            $queryResults = $stmt->fetchAll();
            //Error catching if the permission Attribute does not exist
            if(count($queryResults)<=0){
                return false;
            }
            $roleName = $queryResults[0]["id"];
            settype($roleName, "Integer");
            return $roleName;
        } catch (PDOException $e) {
            echo "Getting Role Name failed: " . $e->getMessage();
        }
    }

    /**
     * @return array|bool
     * array: Gets all permissions of a role and returns them as a id
     * false: The role doesn't exist
     */
    private function fetchPermissions(){
        try {
            if ($this->roleExists()) {
                $stmt = Config::dbCon()->prepare("SELECT  permission_id FROM role INNER JOIN  role_has_permission ON role.id=role_has_permission.role_id WHERE role_id=:roleID");
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
            echo "Fetching permission ID failed: " . $e->getMessage();
        }
    }

    /**
     * @return array
     * Compares the the ids of the permission array of the object and the ids of the permission table and puts the name
     * inside an array
     */
    private function evaluatePermissionNames(){
        $permissionTable = $this->fetchPermissionTable();
        $permissionIDs = $this->permissionIDs;
        $permissionNames = array();
        $index = 0;
        foreach ($permissionIDs as $permission){
            foreach ($permissionTable as $permissionEntry){
                $permissionID = $permissionEntry["id"];
                $permissionName = $permissionEntry["name"];
                //Compared ids of the permissionID inside the object and
                // the id inside the permission entry
                if($permission == $permissionID){
                    $permissionNames[$index] = $permissionName;
                    $index++;
                }
            }
        }
        return $permissionNames;
    }

    /**
     * @return array
     * Gets all available permissions inside the database table
     */
    private function fetchPermissionTable(){
        try {
            $stmt = Config::dbCon()->prepare("SELECT * FROM permissions");
            $stmt->execute();
            $queryResults = $stmt->fetchAll();
            $permissions = array();
            $index = 0;
            foreach ($queryResults as $key=>$permission){
                $permissions[$index]["id"] = $permission["id"];
                $permissions[$index]["name"] = $permission["name"];
                $index++;
            }
            return $permissions;
        } catch (PDOException $e) {
            echo "Fetching permission table failed: " . $e->getMessage();
        }
    }

    /**
     * @return array|bool
     * Gets all role entries to return them to a function so they can be displayed on the website
     * array: Roles are available and can be returned
     * false: No Roles are inside the table
     */
    static public function fetchRoleTable(){
        try {
            $stmt = Config::dbCon()->prepare("SELECT * FROM role");
            $stmt->execute();
            $queryResults = $stmt->fetchAll();
            $permissions = array();
            $index = 0;
            if(count($queryResults) > 0){
                foreach ($queryResults as $key=>$permission){
                    $permissions[$index]["id"] = $permission["id"];
                    $permissions[$index]["name"] = $permission["name"];
                    $index++;
                }
                return $permissions;
            }
            return false;
        } catch (PDOException $e) {
            echo "Fetching permission table failed: " . $e->getMessage();
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
     * @return bool
     * The permissionID that should be added to the role
     * true: Role was successfully added to the object
     * false: The role already exists and the permission can't be added to the object.
     * The permission has to be deleted first
     */
    public function addPermission($id)
    {
        if ($this->exists === false){
            $sizeofPermission = count($this->permissionIDs);
            $this->permissionIDs[$sizeofPermission] = $id;
            return true;
        } else {
            return false;
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
        return $this->roleID;
    }

    /**
     * @return array
     * Returns the permissions of the user as a number
     */
    public function getPermissionIDs()
    {
        return $this->permissionIDs;
    }

    /**
     * @return array
     * Returns the permission attributes of a role as the specific role name
     */
    public function getPermissionsAsName()
    {
        return $this->permissionsAsName;
    }
}
