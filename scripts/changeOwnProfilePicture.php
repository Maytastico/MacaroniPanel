<?php
//Loads all classes
include_once "../_includes/autoloader.inc.php";
//The Loader jumps one directories back
Loader::jump(1);
$back = empty(htmlspecialchars($_GET['r'])) ? "" : $_GET['r'];
$folder = Config::getFolder();

//Initializes a new Authenticator object. To access and validate the user session.
$u = new Authenticator(Authenticator::fetchSessionUserName());
$file = new Uploader("profilePicture", "/userfiles/" . $u->getUserId());
//Checks whether the user is logged in and whether the sessionID is valid
if ($u->verifySession() === false /*|| !$u->hasPermission("usersettings.upload")*/) {
    //In case the sessionID isn't valid the script will give the user feedback
    header("Location: " . $folder . $back . "?changeProfilePicture=noPermission");
    exit();
} else if ($u->verifySession() === true) {
    if ($file->getErrorCode() === 4) {
        header("Location: " . $folder . $back . "?changeProfilePicture=noFile");
        exit();
    } else {
        if (!$file->fileSizeAllowed()) {
            header("Location: " . $folder . $back . "?changeProfilePicture=tooBig");
            exit();
        } else {
            if (!$file->fileIsPicture()) {
                header("Location: " . $folder . $back . "?changeProfilePicture=notAPicture");
                exit();
            } else {
                $file = $file->moveFileToTarget();
                if($file === false){
                    header("Location: " . $folder . $back . "?changeProfilePicture=errorWhileUpload");
                    exit();
                }elseif ($file instanceof File){
                    $file->setDescription("Profile Picture of " . $u->getUsername());
                    $file->addUserID($u->getUserId());
                    $file->addFileToDatabase();
                    $file->reloadingData();
                    var_dump($file->getFileID());
                    if($u->updateCurrentProfilePicture($file->getFileID())){
                        header("Location: " . $folder . $back . "?changeProfilePicture=success");
                        exit();
                    }
                    header("Location: " . $folder . $back . "?changeProfilePicture=databaseError");
                    exit();
                }
            }
        }
    }
} else {
    echo "Authentication error!";
}

