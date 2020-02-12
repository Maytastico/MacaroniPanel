<?php
    $u = new User(Authenticator::fetchSessionUserName());
    $getChange = !empty($_GET['changeUserInfo']) ? $_GET['changeUserInfo'] : null ;
    $getPwChange = !empty($_GET['changePassword']) ? $_GET['changePassword'] : null ;
?>
<div id="editUser" class="box centered width50 main-wrapper <?php if(!empty($getChange) || !empty($getPwChange)){echo "open";}?>">
    <button class="userSettings icon">
        <img src="<?php echo Loader::$jump ?>/assets/icons/feather/x-circle.svg">
    </button>
    <section id="profilePicture" class="changePic">
        <img src="<?php echo Loader::$jump ?>/assets/icons/feather/user-plus.svg">
    </section>
    <form class="editUser" action="<?php echo Loader::$jump?>scripts/changeOwnUserInfo.php?r=/dashboard" method="post">
        <?php
        if ($getChange == "uidtaken") {
            echo '<div class="red"><input type="text" name="uid" placeholder=" ' . $u->getUsername() .' " class="red" value="' . $getUid . '"> There is already a user with this username!</div>';
        } elseif ($getChange == "isAdmin") {
            echo '<div class="red"><input type="text" name="uid" placeholder="' . Authenticator::fetchSessionUserName() .'" class="red" value="' . $getUid . '"><br> Do not chose "admin" as a username' .
                '!</div>';
        } else {
            echo '<input type="text" name="uid" placeholder="' . $u->getUsername() .'" value="' . $getUid . '">';
        }

        if ($getChange == "email")
            echo '<div class="red"><input type="text" name="email" placeholder="' . $u->getEmail() .'" class="red" value="' . $getEmail . '"><br>The format is incorrect!</div>';
        else {
            echo '<input type="text" name="email" placeholder="' . $u->getEmail() .'" value="' . $getEmail . '">';
        }
        
        if ($getChange == "success") {
            echo '<div class="success">Editing user was successful</div>';
        }elseif ($getChange == "noPermission"){
            echo '<div class="red">You do not have the permission to edit properties of a user</div>';
        }

        ?>


        <button class="link changePw" type="button">Change password</button>
        <button type="submit" name="submit">Save changes</button>
    </form>

    <div id="pwBox" class=" box <?php if(!empty($getPwChange)){echo "open";}?>">
        <button class="changePw icon">
            <img src="<?php echo Loader::$jump ?>/assets/icons/feather/x-circle.svg">
        </button>
        <form action="<?php echo Loader::$jump?>scripts/changeOwnPassword.php?r=/dashboard" method="post">
            <input type="password" placeholder="Old password" name="oldPW">
            <input type="password" placeholder="New password" name="newPW">
            <button type="submit">Change Password</button>
            <?php
                if ($getPwChange == "success") {
                    echo '<div class="success">Editing password was successful</div>';
                }elseif ($getPwChange == "noPermission"){
                    echo '<div class="red">You do not have the permission to edit properties of a user</div>';
                }elseif ($getPwChange == "wrongPassword"){
                    echo '<div class="red">Your old Password was not right!</div>';
                }elseif ($getPwChange == "passwordLength"){
                    echo '<div class="red">Your password should have more than 8 characters!</div>';
                }elseif ($getPwChange == "empty"){
                    echo '<div class="red">You forget to fill up a form</div>';
                }
            ?>
        </form>
    </div>

    <div id="picUpload" class="box">
        <button class="changePic icon">
            <img src="<?php echo Loader::$jump ?>/assets/icons/feather/x-circle.svg">
        </button>
        <form method="post" enctype="multipart/form-data" action="<?php echo Loader::$jump?>/scripts/changeOwnProfilePicture.php?r=/dashboard">
            <input type="file" name="profilePicture">
            <button type="submit">Upload Photo</button>
        </form>
    </div>
</div>

