<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 28.10.2019
 * Time: 22:18
 */
include_once "../assets/php/Config.php";
include_once "../_includes/autoloader.inc.php";
Loader::$jump = "../";
$action = empty($_POST['action']) ? false : $_POST['action'];
$remove = empty($_POST['acceptRemove']) ? false : $_POST['acceptRemove'];

if (!$action || !$remove || Install::installAllowed() === false) {
    //If nothing was posted or installing tables is disabled, the user will
    //redirected to the install page.
    header("Location: ../install/index.php?install=noPermission");
    exit();
} elseif (Install::installAllowed() === true || Install::installAllowed() === null) {
    //If the Value of "installMode" is true or nothing was written inside the Database
    //this codeblock will be executed.
    $installObj = new Install();//<--Initializes a new Install-Object
    if ($action === "install") {
        //If the value of $action is "install" (= press on "Install Tables"),
        //it will write the tables into the database and will redirect to the install page.
        $installObj->installTables();
        $installObj->writePermissions();
        $installObj->writeRoles();
        header("Location: ../install/index.php?install=success");
        exit();
    } else {
        if (Install::installAllowed() !== null) {
            //This should be executed, when tables were installed on the database
            //And installAllowed doesn't return null
            //NUll will returned if the tables do not exist
            //This action should be executed if the tables were installed.
            if ($action === "lockup") {
                //Locks the installation mode so no action can be done on the "install UI
                $installObj->lockInstall();
                header("Location: ../install/index.php");
                exit();
            } else {
                //Installs the basic role model for the RBAC System so users can be added
                if($action === "reinstallRoleModel"){
                    $installObj->removeRoles();
                    $installObj->removePermissions();
                    $installObj->writePermissions();
                    $installObj->writeRoles();
                    header("Location: ../install/index.php?roleModel=success");
                    exit();
                }else{
                    if ($action === "reinstall" && $remove === "accept") {
                        //Deleted tables and installs the tables again them
                        $installObj->deleteTables();
                        $installObj->installTables();
                        header("Location: ../install/index.php?reinstall=success");
                        exit();
                    } else {
                        header("Location: ../install/index.php?reinstall=acceptRemoval");
                    }
                }
            }
        }else{
            header("Location: ../install/index.php?install=noPermission");
        }
    }
}
