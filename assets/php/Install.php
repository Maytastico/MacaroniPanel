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
        $this->dbh = Config::dbCon();
    }

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

    static function lockInstall()
    {
        $dhb = Config::dbCon();
        try {
            $sql = "update settings set value='false'where name='installMode';";
            $dhb->query($sql);
            return;
        } catch (PDOException $e) {
            echo "Locking installation failed: " . $e->getMessage();
        }
    }
}