<?php
spl_autoload_register("myAutoloader");

function myAutoloader($className){
$path= "classes/";
$extension = ".class.php";
$fullPath = $path . $className .  $extension;

include_once $fullPath;
}