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

$u = new User(Authenticator::fetchSessionUserName());
var_dump($u);
var_dump($u->createUserDir());


/*$file = new File(DIRECTORY_SEPARATOR ."userfiles" . DIRECTORY_SEPARATOR . "mandeus", "mandeus.txt");
var_dump($file);
/*$file->addUserID(1);
$file->addUserID(2);
var_dump($file->addFileToDatabase());
echo date(filemtime($file->getAbsolutePath()));
FILE::removeAllUserRelations(1);*/





echo  "</pre>";
