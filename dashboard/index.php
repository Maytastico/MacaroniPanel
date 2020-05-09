<?php
include_once "../_includes/autoloader.inc.php";
Loader::jump(1);
$aRes = Authenticator::fetchSessionUserName();

$a = "";
if($aRes !== true){
    $a = new Authenticator(Authenticator::fetchSessionUserName());
    include_once "../_includes/header.inc.php";
    Loader::importJavaScript("Dialog.js");
    Loader::importJavaScript("userSettings.js");
    Loader::importJavaScript("navigation.js");
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
    <pre>
        <?php var_dump($a);?>
    </pre>
    <?php include_once "../templates/navigation.php"?>
    <?php include_once "../templates/userSettings.php"?>
</body>