<?php

//Loads all classes
include_once "../_includes/autoloader.inc.php";
//The Loader jumps one directories back
Loader::jump(1);
$oldPassword = empty(htmlspecialchars(trim($_POST['oldPW']))) ? false : $_POST['oldPW'];
$newPassword = empty(htmlspecialchars($_POST['newPW'])) ? false : $_POST['newPW'];
$back = empty(htmlspecialchars($_GET['r'])) ? "" : $_GET['r'];
$folder = Config::getFolder();

//Initializes a new Authenticator object. To access and validate the user session.
$u = new Authenticator(Authenticator::fetchSessionUserName());
//Checks whether the user is logged in and whether the sessionID is valid
if ($u->verifySession() === false) {
    //In case the sessionID isn't valid the script will give the user feedback
    header("Location: " . $folder . $back . "?changeUserInfo=noPermission");
    exit();
} else if ($u->verifySession() === true) {
    //Checks if all fields where set.
    if($oldPassword === false || $newPassword === false){
        header("Location: " . $folder . $back . "?changePassword=empty");
        exit();
    }else{
        //Assures that the password has more than eight characters
        if(strlen($newPassword)<8){
            header("Location: " . $folder . $back . "?changePassword=passwordLength");
            exit();
        }else{
            //Compares the old hash password with old password the user has put in.
            //If it is right the method will update the old hash with the new hash
            if($u->updatePassword($oldPassword, $newPassword)===false){
                //Is the Password wrong the user will get a feedback.
                header("Location: " . $folder . $back . "?changePassword=wrongPassword");
                exit();
            }
        }
    }
    //Directs the user back to the dashboard with a "success" message
    header("Location: " . $folder . $back . "?changePassword=success");
} else {
    echo "Authentication error!";
}

