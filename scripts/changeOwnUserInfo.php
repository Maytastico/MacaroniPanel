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
//Checks whether the user is logged in and whether the sessionID is valid
if($u->verifySession()===false){
    //In case the sessionID isn't valid the script will give the user feedback
    header("Location: " . $folder . $back . "?changeUserInfo=noPermission");
    exit();
}else if($u->verifySession()===true){
    //---Checks for changing the username---
    //Checks whether name is admin to assure the user is not able to call itself admin.
    if(strtolower($u_name) == "admin"){
        header("Location: " . $folder . $back . "?changeUserInfo=isAdmin");
        exit();
    }else {
        //Checks whether the username field isn't empty and whether it was set.
        if (isset($u_name) && $u_name !== false) {
            //Checks whether the username already exists
            if ($u->updateUsername($u_name) === false) {
                //User will be send back to the dashboard with the message that the user is already taken
                header("Location: " . $folder . $back . "?changeUserInfo=uidtaken");
                exit();
            }
        }
    }
    //---Checks for changing the Email---
    //Checks whether the email field isn't empty and whether is was set
    if(isset($u_email) && $u_email!==false){
        //Assures that the Email is in the right format
        if (!filter_var($u_email, FILTER_VALIDATE_EMAIL)){
            header("Location: " . $folder . $back . "?changeUserInfo=email");
            exit();
        }else{
            //Updates the email of the user that is logged in
            if($u->updateEmail($u_email) && $u_email === false){
                echo "Error while updating email!";
                exit();
            }
        }
    }

    //Writes new Session Data properties because the username changed
    $u->writeSessionData();
    //Directs the user back to the dashboard with a "success" message
    header("Location: " . $folder . $back . "?changeUserInfo=success");
}else{
    echo "Authentication error!";
}

