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
echo "<pre style='color: white'>";
$rbac = new RBAC("Moderator");
$rbac->addPermission(1);
$rbac->addPermission(3);
var_dump($rbac->createRole());

$u = new User("Adre");
var_dump($u->addUser("d@d.com", "manu", 1));
var_dump($u->getRbac()->roleExists());
var_dump($u);

var_dump(RBAC::fetchRoleNameFormID(2));
echo  "</pre>";
