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

        $this->roleID = $this->getRoleIDFromName();

    }

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
            return;
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
            if (count($this->permissionIDs) > 1) {
                if (!$this->roleExists()) {
                    $evaluatedRoleID = $this->evaluateID();
                    $stmt = $this->dbh->prepare("INSERT INTO role(roleID, name, permissionID) VALUES (:roleID,:roleName, :permissionID);");
                    foreach ($this->permissionIDs as $permission) {
                        var_dump($permission);
                        settype($permission, "integer");
                        $stmt->bindParam(":roleID", $evaluatedRoleID);
                        $stmt->bindParam(":roleName", $this->roleName);
                        $stmt->bindParam(":permissionID", $permission);
                        $stmt->execute();
                    }
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
     * @return integer
     * Returns the highest roleID inside the role Table
     */
     private function getRoleIDFromName()
    {
        try {
            if($this->roleExists()) {
                $stmt = $this->dbh->prepare("SELECT id FROM role WHERE name=:roleName");
                $stmt->bindParam("roleName", $this->roleName);
                $stmt->execute();
                $queryResults = $stmt->fetchAll();
                $roleID = $queryResults[0][0];
                settype($roleID, "Integer");
                return $roleID;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Getting Role ID failed: " . $e;
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
        settype($this->roleID, "Integer");
        return $this->roleID;
    }
}
