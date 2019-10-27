<?php
spl_autoload_register("myAutoloader");

function myAutoloader($className)
{
    $path = "assets/php/";
    $extension = ".php";
    $fullPath = $path . $className . $extension;
    if(!file_exists($fullPath)) return;

    include_once $fullPath;
}