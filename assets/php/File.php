<?php

//If you are curious about how this work, you can find the documentation to this class on my GitHub page
//https://github.com/MacaroniDamage/macaronipanel-development/blob/master/Fileusage.md
class File
{

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
        $this->fileName = $filename;
        $this->folderPath = Config::getFolder() . $dir;
    }

    /**
     * @return bool
     * Checks whether a file exists on the hard disk.
     */
    public function fileExistsInDir(){
        return file_exists($this->getFullPath());
    }

    /**
     *
     */
    public function fileExistsInDatabase(){
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM files WHERE fileName=:uid");
            $stmt->bindParam(":uid", $this->username);
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
            echo "Getting data from users failed: " . $e->getMessage();
            return;
        }
    }

    /**
     * @return string
     * Returns an absolute path to the file, so a script can work with the file.
     * For example checking whether the file exists.
     */
    public function getFullPath(){
        return $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $this->getFolderPath();
    }

    /**
     * @return string
     * Concatenates the relative path to the file and file name.
     * Please set the $jump variable of the Loader class with Loader:jump(), so the
     * returned path can be opened properly inside the browser.
     * This is useful for embedding images to a webpage.
     */
    public function getRelativePath(){
        return Config::getFolder() . $this->folderPath . DIRECTORY_SEPARATOR . $this->fileName;
    }
}