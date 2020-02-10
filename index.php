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
$file = new File(DIRECTORY_SEPARATOR ."userfiles" . DIRECTORY_SEPARATOR . "1", "100-peitschenhiebe.jpg");
var_dump($file->fileExistsInDir());
var_dump($file->addUserID(User::getUserIDFromUsername("MacaroniJeff1")));
var_dump($file);
var_dump($file->addFileToDatabase());
var_dump($u->updateCurrentProfilePicture(3));
var_dump( $u->getCurrentProfilePicture());

/*$file->addUserID(1);
$file->addUserID(2);
var_dump($file->addFileToDatabase());
echo date(filemtime($file->getAbsolutePath()));
FILE::removeAllUserRelations(1);*/





echo  "</pre>";
