<nav>
    <div id="button">
        <img src="<?php Loader::$jump?>/assets/icons/feather/menu.svg">
    </div>
    <section id="content">
    </section>
    <section id="user">
        <div>
            <img src="<?php Loader::$jump?>/assets/icons/feather/user.svg">
        </div>
        <div>
            Welcome
            <?php echo Authenticator::fetchSessionUserName();?>
        </div>
        <div id="userButton">
            <img src="<?php Loader::$jump?>/assets/icons/feather/more-vertical.svg">
        </div>
    </section>
    <section id="userMenue">
        <div>
            <form action="<?php Loader::$jump?>/scripts/logout.php">
                <button class="red" name="logout" type="submit">Logout</button>
                <button>User Settings</button>
            </form>
        </div>

    </section>

</nav>
