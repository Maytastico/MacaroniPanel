<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 27.10.2019
 * Time: 01:12
 */

class Install
{
    /**
     * @var PDO
     */
    private $dbh;

    /**
     * @var array
     * Contains the basic permission attributes
     */
    private $basicPermissions = array(
        "usermanager.addUser",
        "usermanager.removeUser",
        "usermanager.editUser",
        "usersettings.upload",
        "adminpanel.show"
    );

    function __construct()
    {
        //Gets the database connection.
        $this->dbh = Config::dbCon();
    }

    /**
     * Installs all tables onto the database.
     * These queries have to be executed in a specific order.
     */
    public function installTables()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS permissions(id int NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(256) NOT NULL)",
            "CREATE TABLE IF NOT EXISTS role(id INTEGER AUTO_INCREMENT NOT NULL PRIMARY KEY , name VARCHAR(256) NOT NULL)",
            "CREATE TABLE IF NOT EXISTS users (user_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, username VARCHAR(256) NOT NULL, password VARCHAR(256) NOT NULL, email VARCHAR(256), lastLogin TIMESTAMP(0), sessionID VARCHAR(32), role_id INTEGER NOT NULL, currentProfilePicture INTEGER )",
            "CREATE TABLE IF NOT EXISTS files(id INTEGER AUTO_INCREMENT NOT NULL PRIMARY KEY , fileName TEXT NOT NULL, dir TEXT NOT NULL,relativePath TEXT NOT NULL , absolutePath TEXT NOT NULL , description TEXT, tags text)",
            "CREATE TABLE IF NOT EXISTS settings(name VARCHAR(256) NOT NULL, value text NOT NULL)",
            "CREATE TABLE IF NOT EXISTS role_has_permission(role_id int NOT NULL , permission_id int NOT NULL, FOREIGN KEY (role_id) REFERENCES role(id), FOREIGN KEY (permission_id) REFERENCES permissions(id))",
            "CREATE TABLE IF NOT EXISTS user_has_file(user_id int NOT NULL , file_id int NOT NULL, FOREIGN KEY (user_id) REFERENCES users(user_id), FOREIGN KEY (file_id) REFERENCES files(id))"
        );

        try {
            foreach ($queries as $query) {
                $this->dbh->query($query);
            }
            $this->writeSettings();
            return;
        } catch (PDOException $e) {
            echo "Installing database tables failed: " . $e->getMessage();
            exit();
        }
    }

    /**
     * Deletes all tables inside the array.
     * They have to executed in a specific order.
     */
    public function deleteTables()
    {
        $queries = array(
            "DROP TABLE user_has_file",
            "DROP TABLE files",
            "DROP TABLE users",
            "DROP TABLE role_has_permission",
            "DROP TABLE role",
            "DROP TABLE permissions",
            "DROP TABLE settings",

        );

        try {
            foreach ($queries as $query) {
                $this->dbh->query($query);
            }
            return;
        } catch (PDOException $e) {
            echo "Purging tables failed: " . $e->getMessage();
            exit();
        }
    }

    /**
     * Writes all entries into the settings table.
     */
    private function writeSettings()
    {
        $queries = array(
            "INSERT INTO settings (name, value) VALUES ('installMode', 'true');"
        );

        try {
            $stmt = $this->dbh->prepare("SELECT * FROM settings");
            $stmt->execute();
            $res = $stmt->fetchAll();
            if (count($res) <= 0) {
                foreach ($queries as $query) {
                    $this->dbh->query($query);
                }
            }
            return;
        } catch (PDOException $e) {
            echo "Writing to settings failed: " . $e->getMessage();
        }
    }

    /**
     * Writes the basic permission model that comes with the application.
     * This function will be used inside the script that handles the installation of the application
     */
    public function removePermissions()
    {
        $permissions = $this->basicPermissions;

        try {
            foreach ($permissions as $permission) {
                $stmt = $this->dbh->prepare("DELETE FROM permissions where name=:permission");
                $stmt->bindParam(":permission", $permission);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            echo "Writing to permissions failed: " . $e->getMessage();
            exit();
        }
    }

    /**
     * Writes the basic permission model that comes with the application.
     * This function will be used inside the script that handles the installation of the application
     */
    public function writePermissions()
    {
        $permissions = $this->basicPermissions;

        try {
            foreach ($permissions as $permission) {
                $stmt = $this->dbh->prepare("INSERT INTO permissions (name) VALUES ( :permission )");
                $stmt->bindParam(":permission", $permission);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            echo "Writing to permissions failed: " . $e->getMessage();
            exit();
        }
    }

    /**
     * @return bool
     * true: Writing roles to the roles table was successful
     * false: Writing roles failed, because the Role already exists
     */
    public function writeRoles()
    {
        $permissions = $this->basicPermissions;
        $rbac = new RBAC("Admin");

        foreach ($permissions as $p)
            $rbac->addPermission(RBAC::fetchPermissionID($p));

        if ($rbac->addRole()) {
            return true;
        }
        return false;
    }

    /**This function will be used on the "Install UI" to manage changes during development.
     *So roles can be added and removed
     * @return bool
     * true: Removing Roles was successful
     * false: Removing Roles failed
     */
    public function removeRoles()
    {
        $rbac = new RBAC("Admin");
        if ($rbac->removeRole()) {
            return true;
        }
        return false;
    }

    /**installAllowed() is a static function that returns the current installation mode
     *from the database. This will be used to verify installation of tables or adding users inside the
     * "Install UI"
     * @return bool | null if no entry was found
     *true will be returned, if installation mode if active.
     *false will be returned, if installation mode was deactivated.
     */
    static function installAllowed()
    {
        $dbh = Config::dbCon();
        try {
            $stmt = $dbh->prepare("SELECT value FROM settings WHERE name='installMode'");
            $stmt->execute();
            $res = $stmt->fetchAll();
            $installState = null;
            if (count($res) > 0) {
                switch ($res[0]["value"]) {
                    case "true":
                        $installState = true;
                        break;
                    case "false":
                        $installState = false;
                        break;
                }
            }
            return $installState;
        } catch (PDOException $e) {
            echo "Getting data from settings failed: " . $e->getMessage();
            return;
        }
    }

    /**
     * lockInstall() overwrites the value inside the settings table.
     * It sets the value of "installMode" to false so the install page isn't
     * accessible. And adding users or modifying tables is not possible
     **/
    public function lockInstall()
    {
        try {
            $sql = "update settings set value='false'where name='installMode';";
            $this->dbh->query($sql);
            return;
        } catch (PDOException $e) {
            echo "Locking installation failed: " . $e->getMessage();
        }
    }
}