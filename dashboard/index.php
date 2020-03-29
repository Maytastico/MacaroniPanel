<?php
include_once "../_includes/autoloader.inc.php";
Loader::jump(1);
include_once "../_includes/header.inc.php";
$aRes = Authenticator::fetchSessionUserName();

$a = "";
if($aRes !== true){
    $a = new Authenticator(Authenticator::fetchSessionUserName());
}
if($a->verifySession()===false){
    $a->resetSession();
    header("Location: ../login.php");
}

$getUid = !empty($_GET['uid']) ? $_GET['uid'] : null ;
$getEmail = !empty($_GET['email']) ? $_GET['email'] : null ;
$getInstall = !empty($_GET['install']) ? $_GET['install'] : null;
$getreinstall = !empty($_GET['reinstall']) ? $_GET['reinstall'] : null ;
$getSignup = !empty($_GET['signup']) ? $_GET['signup'] : null ;
$getRoleModel = !empty($_GET['roleModel']) ? $_GET['roleModel'] : null ;
?>

<body id="dashboard">
    <?php include_once "../templates/navigation.php"?>
    <?php include_once "../templates/userSettings.php"?>
</body>