<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 18.11.2019
 * Time: 20:21
 */

class RBAC
{
    /**@var PDO
     * Saves the database handler
     */
    private $dbh;
    /**@var array
     * Saves all permissions of a role
     */
    private $permissionIDs = array();
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
            $this->roleID = $this->getRoleIDFromName();
        }

    }

    /**
     * @return bool|null
     * true = Role Exists
     * false = Role does not exist
     * null = Some strange is going on
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
            echo "Getting data from role failed: " . $e->getMessage();
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
                    $this->roleID = $this->getRoleIDFromName();
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
     * Gets the role id and permission ids from the attributes and add them into the bridge table.
     * It contains which role has which permission.
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
    private function getRoleIDFromName()
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
    private function updateRoleID(){
        if($this->roleExists()){
            $this->roleID = $this->getRoleIDFromName();
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
        $sizeofPermission = count($this->permissionIDs);
        $this->permissionIDs[$sizeofPermission] = $id;
    }

    /**
     * @param $roleName
     * Name of the role the should be created
     */
    public function setRoleName($roleName)
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
}
