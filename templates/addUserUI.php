<div class="width50 main-wrapper">
    <form class="signUp" action="<?php echo Loader::$jump?>scripts/addUser.php?r=/install/index.php" method="post">
        <h2>Add a account</h2>
        <?php
        if ($getChange == "uidtaken") {
            echo '<div class="red"><input type="text" name="uid" placeholder="Username" class="red" value="' . $getUid . '"> There is already a user with this username!</div>';
        } elseif ($getChange == "isAdmin") {
            echo '<div class="red"><input type="text" name="uid" placeholder="Username" class="red" value="' . $getUid . '"> Do not chose "admin" as a username' .
                '!</div>';
        } else {
            echo '<input type="text" name="uid" placeholder="Username" value="' . $getUid . '">';
        }

        if ($getChange == "email")
            echo '<div class="red"><input type="text" name="email" placeholder="E-mail" class="red" value="' . $getEmail . '">The format is incorrect!</div>';
        else {
            echo '<input type="text" name="email" placeholder="E-mail" value="' . $getEmail . '">';
        }

        if ($getChange == "passwordLength") {
            echo '<div class="red"><input type="password" name="pwd" placeholder="Password" class="red"> Your password should be greater than 8 characters!</div>';
        } else {
            echo '<input type="password" name="pwd" placeholder="Password">';
        }



        RBACContent::showAvailableRolesAsDropdown();
        if ($getChange == "success") {
            echo '<div class="success">Regestration was successful</div>';
        }elseif ($getChange == "noPermission"){
            echo '<div class="red">You do not have the permission to add a user</div>';
        }

        ?>



        <button type="submit" name="submit">Sign up</button>
    </form>
</div>