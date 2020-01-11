<?php

$getFeedback = !empty($_GET['signin']) ? $_GET['signin'] : null ;
$uid = !empty($_GET['uid']) ? $_GET['uid'] : null ;
include_once "_includes/autoloader.inc.php";
Loader::jump(0);
include_once "_includes/header.inc.php";
$aRes = Authenticator::fetchSessionUserName();
if($aRes !== false){
    header("Location: ./dashboard");
}
?>
<body id="login">
    <section id="loginBox">
        <div class="container">
            <section class="row main-container">
                <div class="col main-wrapper">
                    <h2>Macaroni Dashboard Login</h2>
                    <form class="signUp" action="./scripts/login.php" method="post">
                        <?php
                        if($getFeedback == "noUid"){
                            echo '<div class="red"><input type="text" name="uid" placeholder="Username" class="red" value="'.$uid.'"> This user does not exist!</div>';
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
                        <?php
                            if ($getFeedback === "empty"){
                                echo'<div class="red">You have forgot to set something into the input fields!</div>';
                            }elseif ($getFeedback === "wrong"){
                                echo'<div class="red">This user does not exist or the password you have put in is wrong!</div>';
                            }
                        ?>
                    </form>
                </div>
            </section>
        </div>
    </section>
</body>
