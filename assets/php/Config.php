<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 27.10.2019
 * Time: 00:52
 */

class Config
{
    //Folder Destination
    private static $folder = "";
    //Database Configuration
    private static $pdoDNS = "mysql:host=localhost;port=3306;dbname=dashboard";
    private static $pdoUser = "root";
    private static $pdoPW = "10-Death";


    //Handels and returns a PDO database object
    public static function dbCon()
    {
        try {
            $dbh = new PDO(self::$pdoDNS, self::$pdoUser, self::$pdoPW);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbh;
        } catch (PDOException $e) {
            echo "<b><div class='wrong'>Connection failed: " . $e->getMessage() . "</div></b>";
            exit();
        }
    }

    /**
     * @return string
     * Returns the Destination of the application for scripts that have
     * to jump to a certain destination back
     */
    public static function getFolder()
    {
        return self::$folder;
    }
}