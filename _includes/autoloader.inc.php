<?php
//Registers all needed php classes and imports them
spl_autoload_register("Loader::myAutoloader");


class Loader
{
    /**
     * @var string
     * Contains the backward jump commands
     */
    static public $jump = "";

    static private $importantScripts = ["session.js", "folder.js"];


    /**
     * @param $className
     * Loads a specific class into a script
     */
    static function myAutoloader($className)
    {
        $dir = dirname(__DIR__);
        $path = $dir . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "php" . DIRECTORY_SEPARATOR;
        $extension = ".php";
        $fullPath = $path . $className . $extension;
        if (!file_exists($fullPath)) return;
        include_once $fullPath;
    }

    /**
     * Generates HTML to import all .css files inside /assets/css
     */
    static function stylesheetLoader()
    {
        $path = self::$jump . "assets/css/";

        if (file_exists($path)) {
            echo "<!--Stylesheets-->\n";
            $dirHandler = opendir($path);
            while ($file = readdir($dirHandler)) {
                if ($file != "." && $file != "..") {
                    $fullPath = $path . $file;
                    echo "<link rel=\"stylesheet\" href=\"" . $fullPath . "\">\n";

                }
            }
            return true;
        }
        return false;
    }

    /**
     * Renders html for importing all js files inside /assets/js folder
     */
    static function javascriptLoader()
    {
        $path = self::$jump . "assets/js/";
        if (file_exists($path)) {
            echo "<!--JavaScript Files-->\n";
            $dirHandler = opendir($path);
            while ($file = readdir($dirHandler)) {
                if ($file != "." && $file != "..") {
                    $path = self::$jump . "assets/js/";
                    $fullPath = $path . $file;
                    echo "<script src=\"" . $fullPath . "\"></script>\n";
                }
            }
            return true;
        }
        return false;
    }

    /**
     * @param $filename
     * $filename: e.q userAdmin.js
     * Loads specific javascript file from the assets folder
     * @return bool
     * true: File was able to be added into the HTML document.
     * false: File doesn't exist.
     */
    static function importJavaScript($filename){
        $filepath = self::$jump . "assets/js/" . $filename;
        if(file_exists($filepath)){
            echo "<script src=\"" . $filepath . "\"></script>\n";
            return true;
        }
        echo($filepath . " couldn't be loaded!");
        return false;
    }

    /**
     * @param $filename
     * $filename: e.q userAdmin.js
     * Loads specific javascript file from the assets folder
     * @return bool
     * true: File was able to be added into the HTML document.
     * false: File doesn't exist.
     */
    static function importBasicScripts(){
        foreach (self::$importantScripts as $filename){
            self::importJavaScript($filename);
        }
    }

    //Creates the jump back prefixes
    static function jump($jumpDirectories)
    {
        settype($jumpDirectories, "integer");
        $jump = "";
        for ($i = 0; $i < $jumpDirectories; $i++) {
            $jump = $jump . "../";
        }
        self::$jump = $jump;
    }
}