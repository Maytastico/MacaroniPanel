<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 27.10.2019
 * Time: 00:52
 */

class Config
{
    //The default user icon that will be shown on the dashboard
    private static $userIcon = "/assets/icons/feather/user.svg";
    //A collection of all file endings that can be uploaded to filesystem
    private static $allowedImageTypes = array( "png","jpeg", "jpg");
    private static $allowedDocumentTypes = array("pdf");
    private static $allowedArchiveTypes = array("zip");
    //The maximum file size for the application.
    //Look at the php.ini file to specify a global maximum file size.
    private static $maxFileSize = 1000000;
    //Folder Destination
    //Add a "/" at the front your foldername
    //Example: foldername is Dashboard
    //The Variable has to look like "/Dashboard"
    private static $folder = ""/*DIRECTORY_SEPARATOR . "MacaroniPanel"*/;
    //Database Configuration
    private static $pdoDNS = "mysql:host=localhost;port=3306;dbname=dashboard";
    private static $pdoUser = "manuel";
    private static $pdoPW = "123";


    /**
     * Handles and returns a PDO database object
     */
    public static function dbCon()
    {
        try {
            $dbh = new PDO(self::$pdoDNS, self::$pdoUser, self::$pdoPW);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbh;
        } catch (PDOException $e) {
            echo "<b><div class='red'>Connection failed: " . $e->getMessage() . "</div></b>";
            exit();
        }
    }

    /**
     * @return string
     * Returns the destination of the application for scripts that have
     * to jump to a certain destination back
     */
    public static function getFolder()
    {
        return self::$folder;
    }

    /**
     * @return string
     * Returns the path to the default user icon
     */
    public static function getUserIcon()
    {
        return  self::$folder . self::$userIcon;
    }

    /**
     * @return array
     * Returns a list of allowed file types that are images
     */
    public static function getAllowedImageTypes()
    {
        return self::$allowedImageTypes;
    }

    /**
     * @return array
     * Organizes all lists with all allowed file types and returns them inside a one dimensional array
     */
    public static function getAllAllowedTypes(){
        $fileTypes = array(self::$allowedArchiveTypes, self::$allowedImageTypes, self::$allowedDocumentTypes);
        $allTypes = array();
        $i = 0;
        foreach ($fileTypes as $types){
            foreach ($types as $type){
                $allTypes[$i] = $type;
                $i = $i + 1;
            }
        }
        return $allTypes;
    }

    /**
     * @return int
     * Returns the defined maximum file size
     */
    public static function getMaxFileSize()
    {
        return self::$maxFileSize;
    }

}