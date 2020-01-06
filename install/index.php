<?php
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
        <div class="row">
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
            <?php if (Authenticator::fetchSessionUserName() !== false) {
                $a=new Authenticator(Authenticator::fetchSessionUserName())?>
                <div class="col">You are logged in as <?php echo $a->getUsername() ?>
                    <form action="../scripts/logout.php" method="post">
                        <button name="submit" value="logout">Logout</button>
                    </form>

                </div>
                <div class="col">
                    <a class="button" href="../dashboard">Back to Dashboard</a>
                </div>
            <?php } else { ?>
                <a class="button" href="../index.php">Login</a>
            <?php } ?>
        </div>
    </div>
    <div id=""class="container">
        <section class="row main-container">
            <?php include_once "../templates/installUI.php"?>

            <?php include_once "../templates/addUserUI.php"?>
        </section>
    </div>
</body>
</html>
