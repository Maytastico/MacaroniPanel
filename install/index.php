<?php
session_start();
$getUid = !empty($_GET['uid']) ? $_GET['uid'] : null ;
$getEmail = !empty($_GET['email']) ? $_GET['email'] : null ;
$getInstall = !empty($_GET['install']) ? $_GET['install'] : null;
$getreinstall = !empty($_GET['reinstall']) ? $_GET['reinstall'] : null ;
$getSignup = !empty($_GET['signup']) ? $_GET['signup'] : null ;
include_once "../_includes/autoloader.inc.php";
Loader::$jump = "../";
include_once "../_includes/header.inc.php";
?>
<body>
<div class="install">
    <?php $install = Install::installAllowed();
    if ($install === false) {
        ?>
        Installation was locked up.
        <br>If you wish to interact with this page, you have to change the "installMode"
        entry inside the settings table to "true".
    <?php }
    if ($install === null) {
        ?>
        No Tables were installed please press <b>"Install Tables"</b>!
    <?php } ?>
</div>
<div class="container">
    <div class="row install">
        <?php if (isset($_SESSION['s_uid']) && isset($_SESSION['s_id'])) { ?>
            <section class="col">
                You are logged in as <?php echo $_SESSION['s_uid'] ?><br>
                with the session ID <?php echo $_SESSION['s_id'] ?><br>
                The SessionID from the database is <?php $data = accountManager::getUserData($_SESSION['s_uid']);
                echo $data[0][5]; ?><br>
                You are a <?php echo accountManager::userExists($_SESSION['s_uid']) ?>
            </section>
            <form class="col signUp " action="../../GamingParadise/dashboard/_includes/logout.inc.php?back=install" ; method="post">
                <button name="submit">Logout</button>
            </form>
        <?php } else { ?>
                <a class="button" href="../index.php" name="submit">Login</a>
        <?php } ?>
    </div>
</div>
<div class="container">
    <section class="row main-container">
        <div class="col">
            <h2>Install/Reinstall Tables</h2>
            <form class="signUp container-fluid" action="../scripts/install.php" method="post">
                <div class="row">
                    <button class="col " type="submit" name="action" value="install">Install Tables</button>
                    <button class="red col" type="submit" name="action" value="lockup">Finish Installation</button>
                </div>
                <div class="row">
                    <button class="col" type="submit" name="action" value="reinstall">Reinstall Tables</button>
                    <div class="col"><input type="checkbox" value="accept" name="acceptRemove">Accept action</div>
                </div>
                <?php
                if ($getInstall == "success") {
                    echo '<div class="success text-center">Installation was successful</div>';
                } elseif ($getreinstall == "success") {
                    echo '<div class="success text-center">Reinstallation was successful</div>';
                } elseif ($getInstall == "noPermission") {
                    echo '<div class="wrong' .
                        ' text-center">Action failed</div>';                } elseif ($getreinstall == "acceptRemoval") {
                    echo '<div class="wrong' .
                        ' text-center">Please accept</div>';
                }
                ?>
            </form>
        </div>
        <div class="col main-wrapper">
            <h2>Add a account</h2>
            <form class="signUp" action="../../GamingParadise/dashboard/_includes/addUser.inc.php?site=/install/index.php" method="post">
                <?php
                if ($getSignup == "uidtaken") {
                    echo '<div class="wrong"><input type="text" name="uid" placeholder="Username" class="wrong" value="' . $getUid . '"> There is already a user with this username!</div>';
                } elseif ($getSignup == "isAdmin") {
                    echo '<div class="wrong"><input type="text" name="uid" placeholder="Username" class="wrong" value="' . $getUid . '"> Do not chose "admin" as a username' .
                        '!</div>';
                } else {
                    echo '<input type="text" name="uid" placeholder="Username" value="' . $getUid . '">';
                }

                if ($getSignup == "email")
                    echo '<div class="wrong"><input type="text" name="email" placeholder="E-mail" class="wrong" value="' . $getEmail . '">The format is incorrect!</div>';
                else {
                    echo '<input type="text" name="email" placeholder="E-mail" value="' . $getEmail . '">';
                }

                if ($getSignup == "passwordLength") {
                    echo '<div class="wrong"><input type="password" name="pwd" placeholder="Password" class="wrong"> Your password should be greater than 8 characters!</div>';
                } else {
                    echo '<input type="password" name="pwd" placeholder="Password">';
                }

                if ($getSignup == "success") {
                    echo '<div class="success">Regestration was successful</div>';
                }
                ?>

                <select name="type">
                    <option value="admin">Admin</option>
                    <option value="moderator">Moderator</option>
                </select>

                <button type="submit" name="submit">Sign up</button>
            </form>
        </div>
    </section>
</div>
</body>
</html>
