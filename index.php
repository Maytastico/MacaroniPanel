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
$rbac->addPermission(1);
$rbac->addPermission(2);
$rbac->setRoleName("legendary");
var_dump($rbac->removeRole());


echo "</pre>";
