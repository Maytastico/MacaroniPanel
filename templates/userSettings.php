<?php
    $u = new User(Authenticator::fetchSessionUserName());
?>
<div id="editUser" class="box centered width50 main-wrapper">
    <button class="userSettings icon">
        <img src="<?php echo Loader::$jump ?>/assets/icons/feather/x-circle.svg">
    </button>
    <section id="profilePicture" class="changePic">
        <img src="<?php echo Loader::$jump ?>/assets/icons/feather/user-plus.svg">
    </section>
    <form class="editUser" action="<?php echo Loader::$jump?>scripts/editUser.php?r=/index.php" method="post">
        <?php
        if ($getSignup == "uidtaken") {
            echo '<div class="red"><input type="text" name="uid" placeholder=" ' . $u->getUsername() .' " class="red" value="' . $getUid . '"> There is already a user with this username!</div>';
        } elseif ($getSignup == "isAdmin") {
            echo '<div class="red"><input type="text" name="uid" placeholder="' . Authenticator::fetchSessionUserName() .'" class="red" value="' . $getUid . '"> Do not chose "admin" as a username' .
                '!</div>';
        } else {
            echo '<input type="text" name="uid" placeholder="' . $u->getUsername() .'" value="' . $getUid . '">';
        }

        if ($getSignup == "email")
            echo '<div class="red"><input type="text" name="email" placeholder="' . $u->getEmail() .'" class="red" value="' . $getEmail . '">The format is incorrect!</div>';
        else {
            echo '<input type="text" name="email" placeholder="' . $u->getEmail() .'" value="' . $getEmail . '">';
        }





        RBACContent::showAvailableRolesAsDropdown();
        if ($getSignup == "success") {
            echo '<div class="success">Editing user was successful</div>';
        }elseif ($getSignup == "noPermission"){
            echo '<div class="red">You do not have the permission to edit properties of a user</div>';
        }

        ?>


        <button class="link changePw" type="button">Change password</button>
        <button type="submit" name="submit">Save changes</button>
    </form>
    <div id="pwBox" class="width50 box ">
        <button class="changePw icon">
            <img src="<?php echo Loader::$jump ?>/assets/icons/feather/x-circle.svg">
        </button>
        <form>
            <input placeholder="Old password" name="oldPW">
            <input placeholder="New password" name="newPW">
            <button type="submit">Change Password</button>
        </form>
    </div>
    <div id="picUpload" class="box">
        <button class="changePic icon">
            <img src="<?php echo Loader::$jump ?>/assets/icons/feather/x-circle.svg">
        </button>
        <form>
            <input type="file" name="profilePicture">
            <button type="submit">Upload Photo</button>
        </form>
    </div>
</div>

