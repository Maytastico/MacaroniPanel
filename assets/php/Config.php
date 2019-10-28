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
    public static $folder = "";
    //Database
    private static $pdoDNS = "mysql:host=localhost;port=3306;dbname=dashboard";
    private static $pdoUser = "root";
    private static $pdoPW = "";

    //Handels and returns a PDO database object
    public static function dbCon()
    {
        try {
            $dbh = new PDO(self::$pdoDNS, self::$pdoUser, self::$pdoPW);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbh;
        } catch (PDOException $e) {
            echo "<b><div class='wrong'>Connection failed: " . $e->getMessage() . "</div></b>";
        }
    }
}