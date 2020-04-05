<?php

//The idea of this class is to make file uploads easier and more secure.
//It checks for all certain conditions, before uploading a file
class Uploader
{
    /**
     * @var bool
     * evaluates whether a target dir were the file will be saved exists on the file system
     */
    private $targetExists = false;
    /**
     * @var File
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
        //Checks whether php actually evaluated some information
        if (count($fileData) > 0) {
            //Checks whether the target folder exists
            if ($targetDir_tmp->fileExistsInDir()) {
                $this->targetExists = true;
                //Puts the evaluated information into the object
                $this->errorCode = $fileData["error"];
                $this->type = $fileData["type"];
                $this->fileSize = $fileData["size"];
                $this->tmpOnServer = $fileData["tmp_name"];
                $this->fileName = $fileData["name"];
                $this->targetDir = $targetDir_tmp;
            }
        }
    }

    /**
     * @return bool
     * true -> the filename of the uploaded file exists already on the target path
     * false -> there is no file inside the target dir that is named like the file the user uploaded
     * Will be needed to do error handling inside a script
     */
    public function fileExistsInTargetPath()
    {
        $check = new File($this->targetDir->getRelativePath(), $this->fileName);
        if ($check->fileExistsInDir())
            return true;
        return false;
    }

    /**
     * @return bool
     * Checks whether the file is evaluated as an image, whether it contains picture data
     * and whether the evaluated file type is allowed
     * Can be used when you just want to upload a pictures to the server by a script.
     */
    public function fileIsPicture()
    {
        //Prepares the data form php that evaluate the file type for the check
        $imageType = explode("/", $this->type);
        $fileType = $imageType[0];
        //Checks what php said about the file
        if ($fileType === "image") {
            //Checks whether the file contains a valid ending
            if ($this->pictureTypeAllowed()) {
                //Reads the file database and looks after a picture size
                //If it finds something, the uploaded file is a valid picture
                if ($this->validateContainsPictureData()) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @return bool
     * Checks whether this picture type is allowed.
     * Reads the allowed image types from the Config and looks whether
     * the evaluated file type fit to the defined ones.
     */
    private function pictureTypeAllowed()
    {
        foreach (Config::getAllowedImageTypes() as $type) {
            $imageType = explode("/", $this->type);
            $imageType = $imageType[1];
            if ($type === $imageType) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return array|false
     * Returns the height/width and image type of the file.
     * It can be used to validate that a file is an image.
     */
    public function getImageData()
    {
        return getimagesize($this->tmpOnServer);
    }

    /**
     * @return bool
     * Checks whether the file contains picture data
     */
    private function validateContainsPictureData()
    {
        if ($this->getImageData() !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     * Checks whether the file type is allowed to be uploaded to the server
     * Reads the file type, that was evaluated by php and the allowed file types defined by the developer
     */
    public function fileTypeAllowed()
    {
        $fileType = explode("/", $this->type);
        $fileType = $fileType[1];
        foreach (Config::getAllAllowedTypes() as $type) {
            if ($type === $fileType) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     * Check whether the file size is not too big
     */
    public function fileSizeAllowed()
    {
        if ($this->errorCode !== 1 || $this->errorCode !== 2) {
            if (Config::getMaxFileSize() >= $this->fileSize) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool|File
     * Checks whether the filetype is allowed and loads the file to the target directory
     * File -> Filename was concatenated with the time to ensure there are no duplicated filenames and
     *         was moved to the wished directory
     * false -> File type is not allowed or upload was unsuccessful
     */
    public function moveFileToTarget()
    {
        //if the upload to the tmp dir is successful
        if ($this->errorCode === 0) {
            //And the file is in between the defined range
            if ($this->fileSizeAllowed()) {
                //And the file ending is allowed
                if ($this->fileTypeAllowed()) {
                    //The path where the file will be moved will be created
                    $targetFilePath = $this->targetDir->getAbsolutePath() . $this->fileName;
                    //A new filename will be written into the file object
                    //And all paths will be regenerated
                    $this->targetDir->setFileName($this->fileName);
                    //Checks whether such a file does not exist inside the target directory
                    if (!$this->targetDir->fileExistsInDir()) {
                        //Moves the file form the temporary folder to the wished target dir
                        if (move_uploaded_file($this->tmpOnServer, $targetFilePath)) {
                            //And returns the object with all the necessary information of the file
                            return $this->targetDir;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * @return int
     * Returns an error code that was evaluated by the php module
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return bool
     * Return whether a target exists
     */
    public function doesTargetExists()
    {
        return $this->targetExists;
    }
}