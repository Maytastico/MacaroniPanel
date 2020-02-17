<nav id="nativeNav">
    <div id="button">
        <img src="<?php echo Loader::$jump?>/assets/icons/feather/menu.svg">
    </div>
    <section id="content">
    </section>
    <section id="user">
        <div class="profilePicture">
            <?php

                echo $a->getCurrentProfilePicture();
            ?>
        </div>
        <div>
            Welcome
            <?php echo Authenticator::fetchSessionUserName();?>
        </div>
        <div id="userButton">
            <img src="<?php echo Loader::$jump?>/assets/icons/feather/more-vertical.svg">
        </div>
    </section>
    <section id="userMenue">
        <div>
            <form action="<?php echo Loader::$jump?>/scripts/logout.php?r=/login.php">
                <button class="red" name="logout" type="submit">Logout</button>
                <button class="userSettings" type="button">User Settings</button>
                <?php
                    //will shows a button that redirectes to an adminpanel when the user has the permission to show the adminpanel
                    if($a->hasPermission("adminpanel.show")){
                        echo '<a id="adminButton" href="./admin.php">Admin Panel</a>';
                    }
                ?>
            </form>
        </div>

    </section>

</nav>
