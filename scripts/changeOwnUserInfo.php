<?php
//Loads all classes
include_once "../_includes/autoloader.inc.php";
//The Loader jumps one directories back
Loader::jump(1);
$u_name = empty(htmlspecialchars(trim($_POST['uid']))) ? false : $_POST['uid'];
$u_email = empty(htmlspecialchars($_POST['email'])) ? false : $_POST['email'];
$back = empty(htmlspecialchars($_GET['r'])) ? "" : $_GET['r'];
$folder = Config::getFolder();

//Initializes a new Authenticator object. To access and validate the user session.
$u = new Authenticator(Authenticator::fetchSessionUserName());
if($u->verifySession()===false){
    header("Location: " . $folder . $back . "?changeUserInfo=noPermission");
    exit();
}else if($u->verifySession()===true){
    if(strtolower($u_name) == "admin"){
        header("Location: " . $folder . $back . "?changeUserInfo=isAdmin");
        exit();
    }else {
        if (isset($u_name) && $u_name !== false) {
            if ($u->updateUsername($u_name) === false) {
                header("Location: " . $folder . $back . "?changeUserInfo=uidtaken");
                exit();
            }
        }
    }

    if(isset($u_email) && $u_email!==false){
        if (!filter_var($u_email, FILTER_VALIDATE_EMAIL)){
            header("Location: " . $folder . $back . "?changeUserInfo=email");
            exit();
        }else{
            if($u->updateEmail($u_email) && $u_email === false){
                echo "Error while updating email!";
                exit();
            }
        }
    }

    $u->writeSessionData();
    header("Location: " . $folder . $back . "?changeUserInfo=success");
}

