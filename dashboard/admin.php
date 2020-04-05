<?php
include_once "../_includes/autoloader.inc.php";
Loader::jump(1);
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
$getUid = !empty($_GET['uid']) ? $_GET['uid'] : null;
$getEmail = !empty($_GET['email']) ? $_GET['email'] : null;
$getRoleModel = !empty($_GET['roleModel']) ? $_GET['roleModel'] : null;
$getSite = !empty($_GET['site']) ? $_GET['site'] : null;
$getMaxEntries = !empty($_GET['maxEntries']) ? $_GET['maxEntries'] : null;

if ($getMaxEntries == null || $getPage == null || $getSite == null) {
    header("Location: ?site=1&page=users&maxEntries=10&oooooooh");
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
settype($getMaxEntries, "Integer");


/**Global redirect values**/
$header = "?site=$getSite&page=$getPage&maxEntries=$getMaxEntries";

/*User interaction*/
if ($postPage !== null) {
    header("Location: ?site=$getSite&page=$postPage&maxEntries=$getMaxEntries");
    exit();
}
if ($postMaxEntries != $getMaxEntries && $postMaxEntries >= 10) {
    header("Location: ?site=$getSite&page=$getPage&maxEntries=$postMaxEntries");
}
if ($postNextSite === true) {
    $getSite = $getSite + 1;
    header("Location: ?site=$getSite&page=$getPage&maxEntries=$getMaxEntries");
} else if ($postLastSite === true) {
    $getSite = $getSite - 1;
    header("Location: ?site=$getSite&page=$getPage&maxEntries=$getMaxEntries");
}
if ($postPage != null) {
    header("Location: ?site=$getSite&page=$postPage&maxEntries=$getMaxEntries");
}

/*Table element that should be shown*/
$table = null;
if($getPage == "users") {
    $table = new UserContent();
    $table->setTableToShow($getSite, $getMaxEntries);
    $table->reloadData();

}
include_once "../_includes/header.inc.php";
?>

<body id="admin">
<nav class="navbar">
    <main class="buttons">
        <section class="left">
            <a href="index.php">
                <div class="icon"><img src="<?php echo Loader::$jump; ?>/assets/icons/feather/skip-back.svg"></div>
            </a>
        </section>
        <section class="middle">
            <form action="admin.php<?php echo $header?>" method="post">
                <button name="page" type="submit" value="users" class="small icon">
                    <div class="icon"><img src="<?php echo Loader::$jump; ?>/assets/icons/feather/user.svg"> Users</div>
                </button>
                <button name="page" type="submit" value="modules" class="small icon">
                    <div class="icon"><img src="<?php echo Loader::$jump; ?>/assets/icons/feather/briefcase.svg">
                        Modules
                    </div>
                </button>
                <button name="page" type="submit" value="permissions" class="small icon">
                    <div class="icon"><img src="<?php echo Loader::$jump;?>/assets/icons/feather/users.svg">
                        Permissions
                    </div>
                </button>
            </form>
        </section>
    </main>
</nav>
<section id="content">
    <form method="post" action="admin.php<?php echo $header?>">
        <div>
            <input name="search">
            <button>Search</button>
        </div>
        <div>
            <section class="flex">
                <?php if ($getSite > 1)
                    echo '<button onclick="triggerLoadingElement()" class="small"  name="lastSite"><img class="invert" src="' . Loader::$jump . '/assets/icons/feather/arrow-left.svg"></button>';
                ?>
                <section class="loadingContainer"><div class="loading hidden"></div></section>
                <select name="maxEntries">
                    <option <?php
                    if ($getMaxEntries == 10)
                        echo " selected "; ?>>10
                    </option>

                    <option<?php
                    if ($getMaxEntries == 20)
                        echo " selected ";
                    ?>>20
                    </option>

                    <option<?php
                    if ($getMaxEntries == 50)
                        echo " selected ";
                    ?>>50
                    </option>

                    <option <?php if ($getMaxEntries == 100) echo " selected "; ?>>
                        100
                    </option>
                </select>
            <?php
                if($getSite<=$table->getSites())
                    echo '<button onclick="triggerLoadingElement()" class="small" name="nextSite"><img class="invert" src="'.Loader::$jump.'/assets/icons/feather/arrow-right.svg"></button>';
                echo "Site: " . $table->getCurrentSite() . "/" . $table->getSites();
            ?>
        </section>
        </div>
    </form>
    <?php
        $table->drawTable();
    ?>
</section>
</body>