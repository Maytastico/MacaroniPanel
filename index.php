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
//var_dump(User::getUserTableAsUserObj());

//$u = new ("MacaroniJeff");
/*$file = new File(DIRECTORY_SEPARATOR ."userfiles" . DIRECTORY_SEPARATOR . "1", "100-peitschenhiebe.jpg");
var_dump($file->fileExistsInDir());
var_dump($file->addUserID(User::getUserIDFromUsername("MacaroniJeff1")));
var_dump($file);
var_dump($file->addFileToDatabase());*/
$table = new UserContent();
//var_dump($table);
$table->setCurrentSite(3);
$table->drawTable();

/*for($i=0;$i<100;$i++){
    $u = new User(uniqid());
    $u->addUser("a@a.com", "123", 2);
}*/






echo  "</pre>";
