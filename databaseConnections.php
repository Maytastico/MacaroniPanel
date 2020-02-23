<script>
    setTimeout(function(){
        reload();
    }, 1000);
    function reload(){
        location.reload();
    }
</script>
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
session_start();
var_dump($_SESSION);
$_SESSION["maxCon"] = 1;
$maxCon = $_SESSION["maxCon"];
settype($maxCon, "Integer");
$db = Config::dbCon();
$stmt = $db->prepare("select id, user, host, db, command, time, state
from information_schema.processlist");

$stmt->execute();
$res = $stmt->fetchAll();
echo "Connections: ".count($res) ;
echo "<br>Max Connections: " . $maxCon;
if(count($res) > $maxCon){
    $_SESSION["maxCon"] = count($res);
    echo "hi";
}
echo "<table class='tableContent'>";
foreach ($res as $key=>$results){
    echo "<tr>";
    foreach ($results as $result){
        echo "<td>";
        echo $result . "<br>";
        echo "</td>";
    }
    echo "</tr>";
}
echo "</table>";


echo "</pre>";
