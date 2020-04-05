<?php
//Loads all php classes
include_once "../_includes/autoloader.inc.php";
//The Loader jumps one directories back
Loader::jump(1);
$u_name = empty(htmlspecialchars(trim($_POST['uid']))) ? false : $_POST['uid'];
$u_email = empty(htmlspecialchars($_POST['email'])) ? false : $_POST['email'];
$u_password = empty(htmlspecialchars($_POST['pwd'])) ? false : $_POST['pwd'];
$u_type = empty(htmlspecialchars($_POST['type'])) ? false : $_POST['type'];
$back = empty(htmlspecialchars($_GET['r'])) ? "" : $_GET['r'];
$folder = Config::getFolder();

//Initializes a new Authenticator object. To access and validate the user session.
$u = new Authenticator(Authenticator::fetchSessionUserName());
//Checks whether the install mode is deactivated or the Session isn't verified
if(Install::installAllowed() === false && ($u->verifySession() === false &&  $u->hasPermission("usermanager.addUser") === false)){
    header("Location: " . $folder . $back . "?signup=noPermission");
    exit();
} else {
    //Checks whether the install mode is activated or whether the sessionID is verified and the user has the permission to add a user.
    if(Install::installAllowed() === true || ($u->verifySession() === true &&  $u->hasPermission("usermanager.addUser") === true)){
        //Checks whether every input field has content
        if(!$u_name || !$u_email || !$u_password || !$u_type){
            header("Location: " . $folder . $back . "?signup=empty&email=$u_email&uid=$u_name");
            exit();
        }else{
            //Assures that the user doesn't choose Admin as an username, because it is a bad decision.
            if($u_name == "Admin" || $u_name == "admin") {
                header("Location: " . $folder . $back . "?signup=isAdmin&email=$u_email&uid=$u_name");
                exit();
            } else {
                //Assures that the user chooses a password that is longer than 8 characters
                if(strlen($u_password)<8){
                    header("Location: " . $folder . $back . "?signup=passwordLength&email=$u_email&uid=$u_name");
                    exit();
                }else{
                    //Assures that the input field called "email" has the format of an email with an at(@) and an point(.)
                    if (!filter_var($u_email, FILTER_VALIDATE_EMAIL)) {
                        header("Location: " . $folder . $back . "?signup=email&email=$u_email&uid=$u_name");
                        exit();
                    }else{
                        //When everything is right, it will check  whether the user does not exist.
                        //A new user will be created, if the username does not exist.
                        $u = new User($u_name);
                        if($u->addUser($u_email, $u_password, RBAC::fetchRoleIDFromName($u_type)) === true){
                            //Creates a new user dir, if everything is right
                            $u->createUserDir();
                            header("Location: " . $folder . $back . "?signup=success");
                            exit();
                        }else{
                            //Username already exists
                            header("Location: " . $folder . $back . "?signup=uidtaken&email=$u_email&uid=$u_name");
                            exit();
                        }
                    }
                }
            }
        }
    }
}