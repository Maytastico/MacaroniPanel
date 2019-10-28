<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 27.10.2019
 * Time: 00:51
 */
include_once "assets/php/Config.php";
include_once "_includes/autoloader.inc.php";
include_once "_includes/header.inc.php";
$user = new User("Alios");
echo "<pre style='color: white'>";
var_dump($user->addUser("d@d.com", "123", "moderator"));
var_dump($user->getPassword());
echo "</pre>";
