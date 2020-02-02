<?php

//If you are curious about how this work, you can find the documentation to this class on my GitHub page
//https://github.com/MacaroniDamage/macaronipanel-development/blob/master/Fileusage.md
class File
{
    private $dbh;
    /**
     * @var array
     * Contains the tags from the database
     */
    private $tags = array();

    /**
     * @var string
     * Contains the filename of the file. This variable will be written inside the constructor.
     */
    private $fileName;

    /**
     * @var string
     * Contains the concatenated filepath from the root of the webbrowser to the file.
     */
    private $folderPath;

    /**
     * @var string
     * Contains the absolute path to the file.
     * This is string is used as an unique identifier inside the database
     */
    private $absolutePath;
    /**
     * @var string
     * Containes the relative path to the file.
     * Can be used to embed a link inside a "href" or "src" attribute
     */
    private $relativePath;
    /**
     * @var string
     * Contains the description of the file form the database
     */
    private $description;

    /**
     * File constructor.
     * @param $dir
     * @param $filename
     * $dir -> Contains the relative path to the file
     * $filename -> Contains the name of the file
     */
    public function __construct($dir, $filename)
    {
        $this->dbh = Config::dbCon();
        $this->fileName = $filename;
        $this->folderPath = Config::getFolder() . $dir;
        $this->absolutePath = $this->evaluateAbsolutePath();
        $this->relativePath = $this->evaluateRelativePath();
    }

    /**
     * @return bool
     * Checks whether a file exists on the hard disk.
     */
    public function fileExistsInDir(){
        return file_exists($this->absolutePath);
    }

    /**
     * @return bool|null
     * true -> Files was added to the database
     * false -> File wasn't added to the database
     * Can be used to determine whether a file should be added to the database
     */
    public function fileExistsInDatabase(){
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM files WHERE absolutePath=:absolutePath");
            $stmt->bindParam(":absolutePath", $this->absolutePath);
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
            echo "Getting data from files failed: " . $e->getMessage();
            exit();
        }
    }

    /**
     * @return bool
     * true -> The entry for this file does not exist inside the database and was added
     * false -> The entry for this absolute path was already saved inside the database
     * You can use this method to add a file that was uploaded by a user to the database, so it is accessible inside the Panel
     */
    public function addFileToDatabase(){
        try {
            if(!$this->fileExistsInDatabase()){
                $encodedTags = $this->encodeTags();
                $stmt = $this->dbh->prepare("INSERT INTO files (fileName, relativePath, absolutePath, description, tags) VALUES (:fileName, :relativePath, :absolutePath, :describtion, :tags)");
                $stmt->bindParam(":fileName", $this->fileName);
                $stmt->bindParam(":relativePath", $this->relativePath);
                $stmt->bindParam(":absolutePath", $this->absolutePath);
                $stmt->bindParam(":describtion", $this->description);
                $stmt->bindParam(":tags", $encodedTags);
                $stmt->execute();
            }else{
                return false;
            }
        } catch (PDOException $e) {
            echo "Getting data from files failed: " . $e->getMessage();
            exit();
        }
    }

    /**
     * @param string $description
     * Will be used to add or change the description for a file inside the object.
     */
    public function setDescription($description)
    {
        $this->description = htmlspecialchars($description);
    }

    /**
     * @param $tag
     * Adds a tag to the array list inside the object
     */
    public function addTag($tag){
        $sizeofTag = count($this->tags);
        $this->tags[$sizeofTag] = $tag;
    }

    /**
     * @return string
     * Returns the content form the array as a comma separated string.
     * Will be used for the tag entry inside the database
     */
    private function encodeTags(){
        $encoded = "";
        foreach ($this->tags as $tag){
            if(!empty($encoded))
                $encoded = $encoded . "," . htmlspecialchars($tag);
            else
                $encoded = $tag;
        }
        return $encoded;
    }

    /**
     * @param $encodedTags
     * @return array
     * Decodes a comma separated string into an array
     * Will be used to decode the string from the database entry, so it fits into the structure of the object
     */
    private function decodeTags($encodedTags){
        $decodedTags = explode(",", $encodedTags);
        return $decodedTags;
    }

    /**
     * @return string
     * Returns an absolute path to the file, so a script can work with the file.
     * For example checking whether the file exists.
     */
    private function evaluateAbsolutePath(){
        return $_SERVER['DOCUMENT_ROOT'] . $this->evaluateRelativePath();
    }

    /**
     * @return string
     * Concatenates the relative path to the file and file name.
     * Please set the $jump variable of the Loader class with Loader:jump(), so the
     * returned path can be opened properly inside the browser.
     * This is useful for embedding files to a webpage.
     */
    private function evaluateRelativePath(){
        return $this->folderPath . DIRECTORY_SEPARATOR . $this->fileName;
    }

    /**
     * @return string
     * Returns the path were the file is located on the system.
     * Can be used to access a file and check for example whether it exists.
     * The absolute path is a unique identifier for the database.
     * It can look on Windows like that:
     * C:\webserver\dashboard\userfiles\1\cat.png
     * And it will be look on Linux like that:
     * /etc/var/www/dashboard/userfiles/1/cat.png
     */
    public function getAbsolutePath()
    {
        return $this->absolutePath;
    }

    /**
     * @return string
     * Returns the relative path were the file is located on the webserver
     * Can be used to ebbed a link to the file.
     * It can look like that:
     * /dashboard/userfiles/1/cat.png
     * <Panelfolder>/userfiles/<userID>/cat.png
     */
    public function getRelativePath()
    {
        return $this->relativePath;
    }
}