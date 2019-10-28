<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 27.10.2019
 * Time: 01:12
 */

class Install
{
    private $dbh;

    function __construct()
    {
        //Gets the database connection.
        $this->dbh = Config::dbCon();
    }

//Writes all tables into the database.
    public function installTables()
    {
        $queries = array(
            "CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, username VARCHAR(256) NOT NULL, password VARCHAR(256) NOT NULL, email VARCHAR(256), lastLogin TIMESTAMP(0), sessionID BIGINT, type VARCHAR(256) NOT NULL)",
            "CREATE TABLE IF NOT EXISTS settings(name VARCHAR(256) NOT NULL, value text NOT NULL)"
        );

        try {
            foreach ($queries as $query) {
                $this->dbh->query($query);
            }
            $this->writeSettings();
            return;
        } catch (PDOException $e) {
            echo "Installing database tables failed: " . $e->getMessage();
        }
    }

//Deletes all Tables inside the array.
    public function deleteTables()
    {
        $queries = array(
            "DROP TABLE settings",
            "DROP TABLE users"
        );

        try {
            foreach ($queries as $query) {
                $this->dbh->query($query);
            }
            return;
        } catch (PDOException $e) {
            echo "Purging tables failed: " . $e->getMessage();
        }
    }

//Writes all entries into the settings table.
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
//installAllowed() is a static function that returns the current installation mode
//from the database
//@return bool | null if no entry was found
//true will be returned, if installation mode if active.
//false will be returned, if installation mode was deactivated.
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
//lockInstall() overwrites the value inside the settings table.
//It sets the value of "installMode" to false so the install page isn't
//accessible.
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