<?php

//The idea of this class is to make file uploads easier and more secure.
class Uploader
{
    /**
     * @var string
     * Contains the dir where the programmer wishes to move the uploaded file to.
     */
    private $targetDir;
    /**
     * @var int
     * This integer has a few values that have different meanings.
     * This is necessary to measure whether the upload is successful.
     * All error codes are described inside the link below.
     * https://www.php.net/manual/en/features.file-upload.errors.php
     */
    private $errorCode;
    /**
     * @var string
     * Contains the file type of uploaded file that was evaluated by php.
     * This is important to validate the file type.
     */
    private $type;
    /**
     * @var string
     * Contains the path to the uploaded file.
     * Will be used to use the path to move is to the wished destination
     */
    private $tmpOnServer;
    /**
     * @var string
     * Contains the name of the original file that was uploaded by the user
     */
    private $fileName;
    /**
     * @var int
     * Contains the file size of the file on the server.
     * This is important to measure whether the file size is within the regulations.
     */
    private $fileSize;

    public function __construct($fieldName, $targetDir)
    {
        $targetDir_tmp = new File($targetDir, "");
        $fileData = $_FILES[$fieldName];
        if(count($fileData)>0){
            if($targetDir_tmp->fileExistsInDir()){
                $this->errorCode = $fileData["error"];
                $this->type = $fileData["type"];
                $this->fileSize = $fileData["size"];
                $this->tmpOnServer= $fileData["tmp_name"];
                $this->fileName = $fileData["name"];
                $this->targetDir = $targetDir_tmp->getAbsolutePath();
            }
        }

    }
}