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
if ($u->verifySession() === false || $u->hasPermission("usersettings.upload") === false) {
    //In case the sessionID isn't valid the script will give the user feedback
    header("Location: " . $folder . $back . "?changeProfilePicture=noPermission");
    exit();
//Checks whether the current section is valid
} else if ($u->verifySession() === true) {
    //Looks after the dir where the picture will moved to.
    if(!$file->doesTargetExists()){
        //Returns a error when the user dir is not available
        header("Location: " . $folder . $back . "?changeProfilePicture=userDir");
        exit();
    }else{
        //if php did not get a file form the user
        if ($file->getErrorCode() === 4) {
            header("Location: " . $folder . $back . "?changeProfilePicture=noFile");
            exit();
        } else {
            //Will give feedback, if the file is bigger then configured in /assets/php/Config.php
            if (!$file->fileSizeAllowed()) {
                header("Location: " . $folder . $back . "?changeProfilePicture=tooBig");
                exit();
            } else {
                //Checks whether the user file is an allowed and valid picture
                if (!$file->fileIsPicture()) {
                    header("Location: " . $folder . $back . "?changeProfilePicture=notAPicture");
                    exit();
                } else {
                    //copies the file from the temp folder to /userfiles/{user id}
                    //and returns an file object with all information about the file in the userfile directory
                    $file = $file->moveFileToTarget();
                    //Checks whether something went wrong
                    if($file === false){
                        header("Location: " . $folder . $back . "?changeProfilePicture=errorWhileUpload");
                        exit();
                    //Checks whether the returned object is a valid File object
                    }elseif ($file instanceof File){
                        //Sets a description
                        $file->setDescription("Profile Picture of " . $u->getUsername());
                        //Adds the user as a privileged user, that can see the picture
                        $file->addUserID($u->getUserId());
                        //Writes the information to the database
                        $file->addFileToDatabase();
                        //Refreshes the data from the objects, gets the information form the database
                        $file->reloadingData();
                        //Adds the picture to the user and sets it as a new picture
                        if($u->updateCurrentProfilePicture($file->getFileID())){
                            header("Location: " . $folder . $back . "?changeProfilePicture=success");
                            exit();
                        }

                    }
                }
            }
        }
    }
} else {
    echo "Authentication error!";
}

