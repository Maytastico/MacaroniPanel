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
$u = new User("Hans");
$u->addUser("d@d.com", "123", RBAC::fetchRoleIDFromName("Admin"));

$a = new Authenticator("Hans");
var_dump($a->hasPermission("usermanager.addUser "));
echo  "</pre>";
