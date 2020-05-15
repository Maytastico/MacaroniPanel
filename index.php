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
Loader::importJavaScript("Table.js");

?>
<head></head>
<body></body>
<script>let table = new Table({tableHeader:{"hi"}}); table.generateTableContainer(table.containerRef)</script>

$rbac = new RBAC("Moderator");
$rbac->addPermission(2);
$rbac->addRole();

