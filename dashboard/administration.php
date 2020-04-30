<?php
include_once "../_includes/autoloader.inc.php";
Loader::jump(1);
$aRes = Authenticator::fetchSessionUserName();
$a = "";
if ($aRes !== true) {
    $a = new Authenticator(Authenticator::fetchSessionUserName());
}
if ($a->verifySession() === false || !($a->hasPermission("adminpanel.show"))) {
    $a->resetSession();
    header("Location: ../login.php");
}

include_once "../_includes/header.inc.php";
?>

<body id="admin" data-csrf="<?php echo $a->getSessionID() ?>">
    <div id="addUserDialog" class="editDialog box centered width50 main-wrapper">
        <button class="flex left addUserButton icon small">
            <img src="<?php echo Loader::$jump ?>/assets/icons/feather/x-circle.svg">
        </button>

        <div class="row userInput">
            <h2>Add an user</h2>
            <input type="text" name="uid" placeholder="Username" value="test">
            <input type="text" name="e-mail" placeholder="E-Mail" value="d@d.com">
            <input type="password" name="pw" placeholder="Password" value="12345678">
            <div><?php RBACContent::showAvailableRolesAsDropdown(); ?></div>
            <div class="dialog"></div>
            <button id="addUser">Add User</button>
        </div>
    </div>
    <div id="editUserDialog" class="editDialog box centered width50 main-wrapper">
        <button class="flex left icon small" onclick="Dialog.closeElement('#editUserDialog');togglePWField();">
            <img src="<?php echo Loader::$jump ?>/assets/icons/feather/x-circle.svg">
        </button>

        <div class="row userInput">
            <h2>Edit an user</h2>
            <a id="showPW" class="link text left">Change Password</a>
            <input class="hidden" type="password" name="pw" placeholder="New password">
            <input type="text" name="uid" placeholder="Username">
            <input type="text" name="email" placeholder="E-Mail">
            <div><?php RBACContent::showAvailableRolesAsDropdown(); ?></div>
            <div class="dialog"></div>
            <button id="editUser">Edit User</button>
        </div>
    </div>
    <section id="content">
        <form class="flex between">
            <div>
                <section class="row">
                    <select name="maxEntries">
                        <option>10</option>
                        <option>20</option>
                        <option>50</option>
                    </select>
                </section>
            </div>
            <div class="flex">
                <div class="addUserButton">
                    <a class="blue small radial"><img src="<?php echo Loader::$jump ?>/assets/icons/feather/user-plus.svg"></a>
                </div>
                <input class="search" name="search" placeholder="Search:">
                <a class="search small">Search</a>
            </div>
        </form>

        <table class='tableContent'>
            <thead></thead>
            <tbody></tbody>
        </table>

        <section id="sites" class="flex">
            <div id="pageBack"><a class="small"><img class="invert"
                                                     src="<?php echo Loader::$jump ?>/assets/icons/feather/arrow-left.svg"></a>
            </div>
            <section class="flex" id="clickableSites"></section>
            <div id="pageForward"><a class="small"><img class="invert"
                                                        src="<?php echo Loader::$jump ?>/assets/icons/feather/arrow-right.svg"></a>
            </div>
        </section>


    </section>
</body>