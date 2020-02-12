<nav>
    <div id="button">
        <img src="<?php echo Loader::$jump?>/assets/icons/feather/menu.svg">
    </div>
    <section id="content">
    </section>
    <section id="user">
        <div class="profilePicture">
            <?php
                $u = new User(Authenticator::fetchSessionUserName());
                echo $u->getCurrentProfilePicture();
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
            </form>
        </div>

    </section>

</nav>
