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
        if (count($fileData) > 0) {
            if ($targetDir_tmp->fileExistsInDir()) {
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
     * Checks whether the file is evaluated as an image, whether it contains picture data
     * and whether the evaluated file type is allowed
     * Can be used when you just want to upload a pictures to the server by a script.
     */
    public function fileIsPicture()
    {
        $imageType = explode("/", $this->type);
        $fileType = $imageType[0];
        if ($fileType === "image") {
            if ($this->pictureTypeAllowed()) {
                if ($this->validateContainsPictureData()) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @return bool
     * Checks whether this picture type is allowed to be uploaded to the server
     */
    public function pictureTypeAllowed()
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
        if($this->errorCode !== 1 || $this->errorCode !== 2) {
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
        if ($this->errorCode === 0) {
            if ($this->fileSizeAllowed()) {
                if ($this->fileTypeAllowed()) {
                    $fileName = time() . $this->fileName;
                    $targetFilePath = $this->targetDir->getAbsolutePath() . $fileName;
                    if (move_uploaded_file($this->tmpOnServer, $targetFilePath)) {
                        $targetFile = new File($this->targetDir->getRelativePath(), $fileName);
                        return $targetFile;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @return int
     * Returns an error code that was evaluated by php
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}