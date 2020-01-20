<div class="width50 main-wrapper">
    <form class="signUp" action="<?php echo Loader::$jump?>scripts/addUser.php?r=/install/index.php" method="post">
        <h2>Add a account</h2>
        <?php
        if ($getSignup == "uidtaken") {
            echo '<div class="red"><input type="text" name="uid" placeholder="Username" class="red" value="' . $getUid . '"> There is already a user with this username!</div>';
        } elseif ($getSignup == "isAdmin") {
            echo '<div class="red"><input type="text" name="uid" placeholder="Username" class="red" value="' . $getUid . '"> Do not chose "admin" as a username' .
                '!</div>';
        } else {
            echo '<input type="text" name="uid" placeholder="Username" value="' . $getUid . '">';
        }

        if ($getSignup == "email")
            echo '<div class="red"><input type="text" name="email" placeholder="E-mail" class="red" value="' . $getEmail . '">The format is incorrect!</div>';
        else {
            echo '<input type="text" name="email" placeholder="E-mail" value="' . $getEmail . '">';
        }

        if ($getSignup == "passwordLength") {
            echo '<div class="red"><input type="password" name="pwd" placeholder="Password" class="red"> Your password should have more than 8 characters!</div>';
        } else {
            echo '<input type="password" name="pwd" placeholder="Password">';
        }



        RBACContent::showAvailableRolesAsDropdown();
        if ($getSignup == "success") {
            echo '<div class="success">Regestration was successful</div>';
        }elseif ($getSignup == "noPermission"){
            echo '<div class="red">You do not have the permission to add a user</div>';
        }

        ?>



        <button type="submit" name="submit">Sign up</button>
    </form>
</div>