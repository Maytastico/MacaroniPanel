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


    public function __construct($dir, $filename)
    {
        $this->fileName = $filename;
        $this->folderPath = Config::getFolder() . $dir;
    }

}