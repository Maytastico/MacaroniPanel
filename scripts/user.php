<?php
include_once "../assets/php/Config.php";
include_once "../_includes/autoloader.inc.php";
Loader::jump(1);
$u_name = empty(htmlspecialchars(trim($_POST['uid']))) ? false : $_POST['uid'];
$u_email = empty(htmlspecialchars($_POST['email'])) ? false : $_POST['email'];
$u_password = empty(htmlspecialchars($_POST['pwd'])) ? false : $_POST['pwd'];
$u_type = empty(htmlspecialchars($_POST['type'])) ? false : $_POST['type'];
$folder = Config::getFolder();
$back = empty(htmlspecialchars($_GET['r'])) ? "" : $_GET['r'];

$u = new Authenticator(Authenticator::fetchSessionUserName());
if(Install::installAllowed() === false && $u->verifySessionID() === false){
    header("Location: " . $folder . $back . "?signup=noPermission");
    exit();
} else {
    if(Install::installAllowed() === true || ($u->verifySessionID() === true &&  $u->hasPermission("usermanager.addUser") === true)){
        if(!$u_name || !$u_email || !$u_password || !$u_type){
            header("Location: " . $folder . $back . "?signup=empty&email=$u_email&uid=$u_name");
            exit();
        }else{
            if($u_name == "Admin" || $u_name == "admin") {
                header("Location: " . $folder . $back . "?signup=isAdmin&email=$u_email&uid=$u_name");
                exit();
            } else {
                if(strlen($u_password)<=8){
                    header("Location: " . $folder . $back . "?signup=passwordLength&email=$u_email&uid=$u_name");
                    exit();
                }else{
                    if (!filter_var($u_email, FILTER_VALIDATE_EMAIL)) {
                        header("Location: " . $folder . $back . "?signup=email&email=$u_email&uid=$u_name");
                        exit();
                    }else{
                        $u = new User($u_name);
                        if($u->addUser($u_email, $u_password, RBAC::fetchRoleIDFromName($u_type)) === true){
                            header("Location: " . $folder . $back . "?signup=success");
                            exit();
                        }else{
                            header("Location: " . $folder . $back . "?signup=uidtaken&email=$u_email&uid=$u_name");
                            exit();
                        }
                    }
                }
            }
        }
    }else{
        header("Location: " . $folder . $back . "?signup=noPermission");
        exit();
    }
}