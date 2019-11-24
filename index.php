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
$rbac = new RBAC("moderator");
$rbac->addPermission(5);
$rbac->addPermission(2);
$rbac->addPermission(1);
$rbac->setRoleName("moderator");
var_dump($rbac->getRoleID());



$rbac->setRoleName("ma");

echo "</pre>";
