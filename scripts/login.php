<?php
//Loads all classes
include_once "../_includes/autoloader.inc.php";
//The Loader jumps one directories back
Loader::jump(1);
$u_name = empty(htmlspecialchars(trim($_POST['uid']))) ? false : $_POST['uid'];
$u_pwd = empty(htmlspecialchars($_POST['pwd'])) ? false : $_POST['pwd'];
$folder = Config::getFolder();

//Looks whether the user had set all fields
if(!$u_name || !$u_pwd){
    header("Location: ../login.php?signin=empty&uid=$u_name");
}else{
    //Creates a new Authenticator object to write validate user data
    $a = new Authenticator($u_name);
    if($a->userExists() === false){
        header("Location: ../login.php?signin=wrong&uid=$u_name");
    }else{
        //Checks whether the user password was right
        $a->setPlainPW($u_pwd);
        if($a->checkPassword() === false){
            header("Location: ../login.php?signin=wrong&uid=$u_name");
        }else{
            if($a->checkPassword()===true){
                //Writes session data to the session container of the user
                $a->writeSessionData();
                header("Location: ../dashboard");
            }
        }
    }
}