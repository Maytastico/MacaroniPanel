<div class="width50">
    <form class="install container-fluid" action="<?php echo Loader::$jump?>/scripts/install.php" method="post">
        <h2>Install/Reinstall Tables</h2>
        <div class="row">
            <button class="col " type="submit" name="action" value="install">Install Tables</button>
            <button class="red col" type="submit" name="action" value="lockup">Finish Installation</button>
        </div>
        <div class="row">
            <button class="col" type="submit" name="action" value="reinstall">Reinstall Tables</button>
            <button class="col" type="submit" name="action" value="reinstallRoleModel">Reinstall Permission Model</button>
        </div>
        <div>
            <div class="col"><input type="checkbox" value="accept" name="acceptRemove">Accept action</div>
        </div>
    </form>
    <div class="messages">
        <?php
        if ($getInstall == "success") {
            echo '<div class="success text-center">Installation was successful</div>';
        } elseif ($getreinstall == "success") {
            echo '<div class="success text-center">Reinstallation was successful</div>';
        } elseif ($getInstall == "noPermission") {
            echo '<div class="red' .
                ' text-center">Action failed</div>';
        } elseif ($getreinstall == "acceptRemoval") {
            echo '<div class="red' .
                ' text-center">Please accept</div>';
        }elseif ($getRoleModel == "success"){
            echo '<div class="success text-center">Reinstalling Permission Model was successful</div>';
        }
        ?>
    </div>
</div>