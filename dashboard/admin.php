<?php
include_once "../_includes/autoloader.inc.php";
Loader::jump(1);
include_once "../_includes/header.inc.php";
$aRes = Authenticator::fetchSessionUserName();
$a = "";
if ($aRes !== true) {
    $a = new Authenticator(Authenticator::fetchSessionUserName());
}
if ($a->verifySession() === false) {
    $a->resetSession();
    header("Location: ../login.php");
}


/**Get Values**/
$getPage = !empty($_GET['page']) ? $_GET['page'] : null;
if ($getPage === null) {
    header("Location: ?site=1&page=users&maxEntries=$getMaxEntries");
}
$getUid = !empty($_GET['uid']) ? $_GET['uid'] : null;
$getEmail = !empty($_GET['email']) ? $_GET['email'] : null;
$getRoleModel = !empty($_GET['roleModel']) ? $_GET['roleModel'] : null;


$getSite = !empty($_GET['site']) ? $_GET['site'] : null;
if ($getSite === null) {
    header("Location: ?site=1&page=$getPage&maxEntries=$getMaxEntries");
}
$getMaxEntries = !empty($_GET['maxEntries']) ? $_GET['maxEntries'] : null;
var_dump($getMaxEntries);
if($getMaxEntries == null){

    header("Location: ?site=$getSite&page=$getPage&maxEntries=25");
}

/**Post Values**/
$postMaxEntries = !empty($_POST['maxEntries']) ? $_POST['maxEntries'] : null;
$postNextSite = isset($_POST['nextSite']);
$postLastSite = isset($_POST['lastSite']);
$postSearch = !empty($_POST['search']) ? $_POST['search'] : null;
$postPage = !empty($_POST['page']) ? $_POST['page'] : null;
/**Defining Datatypes**/
settype($getSite, "Integer");
settype($postMaxEntries, "Integer");

/*Global header*/
$header = "?site=$getSite&page=$getPage&maxEntries=$getMaxEntries";
var_dump($_POST);
if ($postMaxEntries !== null) {
    var_dump($postMaxEntries);
    header("Location: ?site=$getSite&page=$getPage&maxEntries=$postMaxEntries");
}else{
    echo "hi";
    if ($postNextSite === true) {
        $getSite = $getSite + 1;
        header("Location: ?site=$getSite&page=$getPage&maxEntries=$getMaxEntries");
    } else if ($postLastSite === true) {
        $getSite = $getSite - 1;
        header("Location: ?site=$getSite&page=$getPage&maxEntries=$getMaxEntries");
    }
}
if ($postPage != null) {
    header("Location: ?site=$getSite&page=$postPage&maxEntries=$getMaxEntries");
}


?>

<body id="admin">
<!--<nav class="navbar">
    <section class="buttons">
        <div class="left">
            <a href="index.php">
                <div class="icon"><img src="<?php /*echo Loader::$jump; */?>/assets/icons/feather/skip-back.svg"></div>
            </a>
        </div>
        <div class="middle">
            <form action="admin.php?site=<?php /*echo $getSite . "&page=" . $getPage */?>" method="post">
                <button name="page" type="submit" value="users" class="small icon">
                    <div class="icon"><img src="<?php /*echo Loader::$jump; */?>/assets/icons/feather/user.svg"> Users</div>
                </button>
                <button name="page" type="submit" value="modules" class="small icon">
                    <div class="icon"><img src="<?php /*echo Loader::$jump; */?>/assets/icons/feather/briefcase.svg">
                        Modules
                    </div>
                </button>
                <button name="page" type="submit" value="permissions" class="small icon">
                    <div class="icon"><img src="<?php /*echo Loader::$jump; */?>/assets/icons/feather/users.svg">
                        Permissions
                    </div>
                </button>
            </form>
        </div>
    </section>
</nav>-->
<section id="content">
    <form method="post" action="admin.php?site=<?php echo $getSite . "&page=" . $getPage ?>">
        <div>
            <input name="search">
            <button>Search</button>
        </div>
        <div>
            <?php if ($getSite > 1)
                echo '<button class="small"  name="lastSite"><img class="invert" src="' . Loader::$jump . '/assets/icons/feather/arrow-left.svg"></button>'; ?>
            <select name="maxEntries">
                <option <?php
                if ($getMaxEntries == 10)
                    echo " selected "; ?>>10
                </option>

                <option<?php
                if ($getMaxEntries  == 20)
                    echo " selected ";
                ?>>20
                </option>

                <option<?php
                if ($getMaxEntries == 50)
                    echo " selected ";
                ?>>50
                </option>

                <option <?php if($getMaxEntries == 100)echo" selected ";?>>
                    100
                </option>
            </select>
            <button class="small" name="nextSite"><img class="invert" src="<?php echo Loader::$jump; ?>/assets/icons/feather/arrow-right.svg"></button>
        </div>
    </form>
    <?php
    if ($getPage == "users"){
        $table = new UserContent();
        $table->setCurrentSite($getSite);
        $table->setMaxEntries($getMaxEntries);
        $table->drawTable();
    }

    ?>
</section>
</body>