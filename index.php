<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 27.10.2019
 * Time: 00:51
 */
include_once "assets/php/Config.php";
include_once "_includes/autoloader.inc.php";
Loader::jump(0);
include_once "_includes/header.inc.php";
echo "<pre>";

$file = new File("userfiles" . DIRECTORY_SEPARATOR . "mandeus", "mandeus.txt");
var_dump($file->fileExistsInDir());
var_dump($file);
var_dump($file->getFolderPath());

var_dump(is_dir($_SERVER['DOCUMENT_ROOT'] ."/userfiles"));





echo  "</pre>";
