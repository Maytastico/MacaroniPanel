<?php
//Loads all classes
include_once "../_includes/autoloader.inc.php";
//The Loader jumps one directories back
Loader::jump(1);
$back = empty(htmlspecialchars($_GET['r'])) ? false : $_GET['r'];
$folder = Config::getFolder();



if(Authenticator::fetchSessionUserName() !== false){
    $a = new Authenticator(Authenticator::fetchSessionUserName());
    $a->resetSession();
    header("Location: " . $folder . $back . "?logout=success");
}
