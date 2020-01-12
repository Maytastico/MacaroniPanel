<?php
include_once "../_includes/autoloader.inc.php";
Loader::jump(1);
include_once "../_includes/header.inc.php";
$aRes = Authenticator::fetchSessionUserName();
if($aRes === false){
    header("Location: ../login.php");
}
?>

<body id="dashboard">
    <?php include_once "../templates/navigation.php"?>
    <h2>asdkjalösdj</h2>
    <h2>asdkjalösdj</h2>
    <h2>asdkjalösdj</h2>
    <h2>asdkjalösdj</h2>
    <h2>asdkjalösdj</h2>
    <h2>asdkjalösdj</h2>
    <h2>asdkjalösdj</h2>
    <h2>asdkjalösdj</h2>
    <h2>asdkjalösdj</h2>
    <h2>asdkjalösdj</h2>
    <h2>asdkjalösdj</h2>
    <h2>asdkjalösdj</h2>
    <h2>asdkjalösdj</h2>
    <h2>asdkjalösdj</h2>
    <h2>asdkjalösdj</h2>
    <h2>asdkjalösdj</h2>
</body>