<?php
include_once "../assets/php/Config.php";
include_once "../_includes/autoloader.inc.php";
Loader::$jump = "../";
$u_name = empty(htmlspecialchars(trim($_POST['uid']))) ? false : $_POST['uid'];
$u_email = empty(htmlspecialchars($_POST['pwd'])) ? false : $_POST['pwd'];
$u_password = empty(htmlspecialchars($_POST['email'])) ? false : $_POST['email'];
$u_type = empty(htmlspecialchars($_POST['type'])) ? false : $_POST['type'];
$folder = Config::getFolder();
$back = empty(htmlspecialchars($_GET['r'])) ? "" : $_GET['r'];

echo $u_email . $u_name . $u_password;
exit();
$s_name = $_SESSION["u_name"];
if(Install::installAllowed() === false){
    header("Location: " . $folder . $back . "?signup=noPermission");
} else {
    if(Install::installAllowed() === true){
        if(!$u_name || !$u_email || !$u_password || !$u_type){
            header("Location: " . $folder . $back . "?signup=empty&email=$email&uid=$uid");
            exit();
        }else{
            if($uid == "Admin" || $uid == "admin") {
                header("Location: " . $folder . $back . "?signup=isAdmin&email=$email&uid=$uid");
                exit();
            }
        }
    }
}