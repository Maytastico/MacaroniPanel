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


/**Get Values**/
$getPage = !empty($_GET['page']) ? $_GET['page'] : null;
if($getPage === null){
    header("Location: ?site=1&page=users");
}
$getUid = !empty($_GET['uid']) ? $_GET['uid'] : null ;
$getEmail = !empty($_GET['email']) ? $_GET['email'] : null ;
$getRoleModel = !empty($_GET['roleModel']) ? $_GET['roleModel'] : null ;


$getSite = !empty($_GET['site']) ? $_GET['site'] : null ;
if($getSite === null){
    header("Location: ?site=1&page=$getPage");
}
$getMax = !empty($_GET['maxEntries']) ? $_GET['maxEntries'] : null;

/**Post Values**/
$postMaxEntries = !empty($_POST['maxEntries']) ? $_POST['maxEntries'] : 20;
$postNextSite = isset($_POST['nextSite']);
$postLastSite = isset($_POST['lastSite']);
$postSearch = !empty($_POST['search']) ? $_POST['search'] : null;
$postPage = !empty($_POST['page']) ? $_POST['page'] : null;
/**Defining Datatypes**/
settype($getSite, "Integer");
settype($postMaxEntries, "Integer");

/*Global header*/
$header = "?site=$getSite&page=$getPage";

if($postNextSite === true){
    $getSite = $getSite + 1;
    header("Location:".$header);
}else if ($postLastSite === true){
    $getSite = $getSite - 1;
    header("Location:".$header);
}
var_dump($postPage);
if($postPage != null){
    header("Location: ?site=$getSite&page=$postPage");
}

?>

<body id="admin">
    <nav class="navbar">
        <section class="buttons">
            <div class="left">
                <a href="index.php"><div class="icon"><img src="<?php echo Loader::$jump;?>/assets/icons/feather/skip-back.svg"></div></a>
            </div>
            <div class="middle">
                <form action="admin.php?site=<?php echo $getSite . "&page=".$getPage?>" method="post">
                    <button name="page" type="submit" value="users" class="small icon" ><div class="icon"><img src="<?php echo Loader::$jump;?>/assets/icons/feather/user.svg"> Users</div></button>
                    <button name="page" type="submit" value="modules" class="small icon"><div class="icon"><img src="<?php echo Loader::$jump;?>/assets/icons/feather/briefcase.svg"> Modules</div></button>
                    <button name="page" type="submit" value="permissions" class="small icon"><div class="icon"><img src="<?php echo Loader::$jump;?>/assets/icons/feather/users.svg"> Permissions</div></button>
                </form>
            </div>
        </section>
    </nav>
    <section id="content">

        <?php var_dump($postMaxEntries);?>
        <form method="post" action="admin.php?site=<?php echo $getSite . "&page=".$getPage?>">
            <div>
                <input name="search">
                <button>Search</button>
            </div>
            <div>
                <?php if($getSite > 1)
                    echo '<button class="small"  name="lastSite"><img class="invert" src="'.Loader::$jump.'/assets/icons/feather/arrow-left.svg"></button>'; ?>
                <select name="maxEntries">
                    <option <?php
                        if($postMaxEntries==10)
                            echo "selected"; ?>>10</option>

                    <option<?php
                        if($postMaxEntries==20)
                            echo "selected";
                    ?>>20</option>

                    <option<?php
                        if($postMaxEntries==50)
                            echo "selected";
                    ?>>50</option>

                    <option<?php
                        if($postMaxEntries==100)
                            echo "selected";
                    ?>>100</option>
                </select>

                <button class="small"  name="nextSite"><img class="invert" src="<?php echo Loader::$jump;?>/assets/icons/feather/arrow-right.svg"></button>
            </div>
        </form>
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

        <?php
            if($getPage)
        ?>
    </section>
</body>