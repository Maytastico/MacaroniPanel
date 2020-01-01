<?php
session_start();
$getUid = !empty($_GET['uid']) ? $_GET['uid'] : null ;
$getEmail = !empty($_GET['email']) ? $_GET['email'] : null ;
$getInstall = !empty($_GET['install']) ? $_GET['install'] : null;
$getreinstall = !empty($_GET['reinstall']) ? $_GET['reinstall'] : null ;
$getSignup = !empty($_GET['signup']) ? $_GET['signup'] : null ;
$getRoleModel = !empty($_GET['roleModel']) ? $_GET['roleModel'] : null ;
include_once "../_includes/autoloader.inc.php";
Loader::jump(1);
include_once "../_includes/header.inc.php";
?>
<body id="install">
    <div class="install">
        <?php $install = Install::installAllowed();
        if ($install === false) {
            ?>
            Installation mode is deactivated.
            <br>If you wish to interact with this page, you have to update the "installMode"
            entry inside the "settings" table to "true".
        <?php }
        if ($install === null) {
            ?>
            No Tables were installed please press <b>"Install Tables"</b>!
        <?php } ?>
        <?php if (isset($_SESSION['s_uid']) && isset($_SESSION['s_id'])) { ?>
            <section class="col">
                You are logged in as <?php echo $_SESSION['s_uid'] ?><br>
                with the session ID <?php echo $_SESSION['s_id'] ?><br>
                The SessionID from the database is <?php $data = accountManager::getUserData($_SESSION['s_uid']);
                echo $data[0][5]; ?><br>
                You are a <?php echo accountManager::userExists($_SESSION['s_uid']) ?>
            </section>
            <form class="width50 col signUp " action="../../GamingParadise/dashboard/_includes/logout.inc.php?back=install" ; method="post">
                <button name="submit">Logout</button>
            </form>
        <?php } else { ?>
            <a class="button" href="../index.php" name="submit">Login</a>
        <?php } ?>
    </div>
    <div id=""class="container">
        <section class="row main-container">
            <?php include_once "../templates/installUI.php"?>

            <?php include_once "../templates/addUserUI.php"?>
        </section>
    </div>
</body>
</html>
