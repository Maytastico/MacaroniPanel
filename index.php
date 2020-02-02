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

$file = new File(DIRECTORY_SEPARATOR ."userfiles" . DIRECTORY_SEPARATOR . "mandeus", "mandeus.txt");
var_dump($file->fileExistsInDir());
var_dump($file);
var_dump($file->fileExistsInDatabase());
$file->setDescription("<br>This is file for mandeus");
$file->addTag("Mandeus");
$file->addTag("KurKur");
$file->addTag("<br>murmur");
var_dump($file->decodeTags("Mandeus,KurKur,&lt;br&gt;murmur"));
var_dump(__FILE__);






echo  "</pre>";
