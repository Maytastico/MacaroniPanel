<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 27.10.2019
 * Time: 00:51
 */
include_once "_includes/Autoloader.php";
$db = new Install();
$db->installTables();
$db = Install::installAllowed();

var_dump(Install::installAllowed());
