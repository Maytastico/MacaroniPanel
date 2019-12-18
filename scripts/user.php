<?php
include_once "../assets/php/Config.php";
include_once "../_includes/autoloader.inc.php";
Loader::$jump = "../";
$action = empty($_POST['action']) ? false : $_POST['action'];
$remove = empty($_POST['acceptRemove']) ? false : $_POST['acceptRemove'];