<?php
session_start();
$getFeedback = !empty($_GET['signin']) ? $_GET['signin'] : null ;
$uid = !empty($_GET['uid']) ? $_GET['uid'] : null ;
include_once "_includes/autoloader.inc.php";
Loader::jump(0);
include_once "_includes/header.inc.php";
?>
<body id="login">
    <section id="loginBox">
        <div class="container">
            <section class="row main-container">
                <div class="col main-wrapper">
                    <h2>MacaroniPanel Login</h2>
                    <form class="signUp" action="./_includes/login.inc.php" method="post">
                        <?php
                        if($getFeedback == "noUid"){
                            echo '<div class="wrong"><input type="text" name="uid" placeholder="Username" class="wrong" value="'.$uid.'"> There is already a user with this username!</div>';
                        }else{
                            echo '<input type="text" name="uid" placeholder="Username" value="'.$uid.'">';
                        }

                        if($getFeedback == "passwordWrong"){
                            echo '<div class="wrong"><input type="password" name="pwd" placeholder="Password" class="wrong"> Your password should be greater than 8 characters!</div>';
                        }else{
                            echo '<input type="password" name="pwd" placeholder="Password">';
                        }
                        ?>
                        <button type="submit" name="submit">Sign in</button>
                    </form>
                </div>
            </section>
        </div>
    </section>
</body>
