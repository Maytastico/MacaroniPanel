<?php


class Uploader
{
    private $targetDir;
    private $errorCode;
    private $type;
    private $tempdir;
    private $fileName;
    private $fileSize;

    public function __construct($fieldName)
    {
        $fileData = $_FILES[$fieldName];
    }
}