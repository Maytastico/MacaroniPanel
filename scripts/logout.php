<?php
//Loads all classes
include_once "../_includes/autoloader.inc.php";
//The Loader jumps one directories back
Loader::jump(1);
$submit = isset($_POST['submit']);
$back = empty(htmlspecialchars($_GET['back'])) ? false : $_GET['pwd'];
$folder = Config::getFolder();


if($submit===false){
    header("Location: ../?logout=error");
    exit();

}else{
    $aRes = Authenticator::fetchSessionUserName();
    if($aRes === false){
        header("Location: ../?logout=error1");
        exit();
    }else{
        if(Authenticator::fetchSessionUserName() !== false){
            $a = new Authenticator(Authenticator::fetchSessionUserName());
            $a->resetSession();
            header("Location: " . $folder . $back . "?logout=success");
        }
        exit();
    }
}
