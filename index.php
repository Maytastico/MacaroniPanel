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


$a = new Authenticator("Manuel");
$a->setPlainPW("1234567");
var_dump($a->checkPassword());





echo  "</pre>";
