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

<body id="admin">
    <nav class="navbar">
        <section class="buttons">
            <div class="left">
                <a href="index.php"><div class="icon"><img src="<?php echo Loader::$jump;?>/assets/icons/feather/skip-back.svg"></div></a>
            </div>
            <div class="middle">
                <a href="?users"><div class="icon"><img src="<?php echo Loader::$jump;?>/assets/icons/feather/user.svg"> Users</div></a>
                <a href="?modules"><div class="icon"><img src="<?php echo Loader::$jump;?>/assets/icons/feather/briefcase.svg"> Modules</div></a>
                <a href="?permissions"><div class="icon"><img src="<?php echo Loader::$jump;?>/assets/icons/feather/users.svg"> Permissions</div></a>
            </div>
        </section>
    </nav>
    <section id="content">
        <table class="tableContent">
            <tr>
                <th>Profile</th>
                <th>Username</th>
                <th>Email</th>
                <th>Last Login</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <tr>
                <td>pic</td>
                <td>MacaroniJeff</td>
                <td>MacaroniJeff@a.com</td>
                <td>12.11.2012</td>
                <td>Admin</td>
                <td>
                    <form action="" method="post">
                        <button class="radial red"><img src="<?php echo Loader::$jump;?>/assets/icons/feather/trash-2.svg"></button>
                        <button class="radial"><img src="<?php echo Loader::$jump;?>/assets/icons/feather/edit.svg"></button>
                    </form>
                </td>
            </tr>
        </table>

    </section>
    <pre

</body>