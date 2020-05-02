<?php
require "../_includes/autoloader.inc.php";

//todo Authorization!!!!
class userAdmin
{
    /**
     * @var Authenticator
     */
    private $requestFromUser;

    public function indexAction()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            //todo check whether a get request was send
            $this->authorize($_GET["csrf"]);
            if ($this->requestFromUser->hasPermission("adminpanel.show"))
                $result = $this->get();
            else
                $this->ForbiddenError();

        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->create($_POST);
        } elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $this->authorize($input["csrf"]);
            if ($this->requestFromUser->hasPermission("usermanager.editUser"))
                $result = $this->update($input);
            else
                $this->ForbiddenError();
        } elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $this->ForbiddenError();
            $this->authorize($input["csrf"]);
            if ($this->requestFromUser->hasPermission("usermanager.removeUser"))
                $this->delete($input);
            else
                $this->ForbiddenError();
        }
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type");
        echo json_encode($result);
    }

    private function get()
    {
        $userObj = User::getUserTable();
        $response = null;
        $i = 0;
        foreach ($userObj as $user) {
            $u = new User($user["username"]);
            $response[$i]["profilePicture"] = $u->getCurrentProfilePicture();
            $response[$i]["username"] = $u->getUsername();
            $response[$i]["email"] = $u->getEmail();
            $response[$i]["lastLogin"] = date("H:i d F y", $u->getLastLogin());
            $response[$i]["role"] = $u->getRbac()->getRoleName();
            $i++;
        }
        return $response;
    }

    private
    function create($input)
    {
        return $input;
    }

    private function update($data)
    {
        if (empty($data["identifierUid"]) || (empty($data["newUid"]) && empty($data["newPW"]) && empty($data["newEmail"]))) {
            $error = ["error" => "empty"];
            echo json_encode($error);
            $this->BadRequestError();
        } else {
            if (strtolower($data["newUid"]) == "admin") {
                $error = ["error" => "admin"];
                echo json_encode($error);
                $this->BadRequestError();
            } else {
                if (strlen($data["newPW"]) <= 8 && !empty($data["newPW"])) {
                    $error = ["error" => "pw"];
                    echo json_encode($error);
                    $this->BadRequestError();
                } else {
                    if (!filter_var($data["newEmail"], FILTER_VALIDATE_EMAIL) && !empty($data["newEmail"])) {
                        $error = ["error" => "email"];
                        echo json_encode($error);
                        $this->BadRequestError();
                    } else {
                        //todo password!
                        $userToEdit = new Authenticator($data["identifierUid"]);
                        if (!empty($data["newUid"])) {
                            if (!$userToEdit->updateUsername($data["newUid"])) {
                                $error = ["error" => "usernameExists"];
                                echo json_encode($error);
                                $this->BadRequestError();
                            }
                            $this->SuccessMessage();
                        }
                        if (!empty($data["newEmail"])) {
                            if (!$userToEdit->updateEmail($data["newEmail"])) {
                                $error = ["error" => "uidDoesNotExist"];
                                echo json_encode($error);
                                $this->BadRequestError();
                            }
                            $this->SuccessMessage();
                        }
                        if ($userToEdit->getRbac()->getRoleName() != $data["role"]) {

                        }
                    }
                }
            }
        }
        return $data;
    }

    private function delete($data)
    {

    }

    private function authorize($csrfToken)
    {
        $authKey = $this->getAuthKey();
        session_id($authKey);
        if (function_exists('getallheaders')) {
            Authenticator::initSession();
            $a = new Authenticator($_SESSION["u_name"]);
            if (!($authKey == "null") || !($csrfToken == "null")) {
                if (($a->getSessionID() === $csrfToken)) {
                    $this->requestFromUser = $a;
                    return;
                }
            }
        }
        $this->AuthenticationError();
    }

    private function getAuthKey()
    {
        if (function_exists('getallheaders')) {
            $header = getallheaders();
            if (isset($header['Authorization'])) {
                return $header['Authorization'];
            }
        }
        return false;
    }

    private function SuccessMessage()
    {
        header("HTTP/1.0 200 OK");
        exit();
    }

    private function AuthenticationError()
    {
        header("HTTP/1.0 401 Unauthorized");
        exit();
    }

    private function ForbiddenError()
    {
        header("HTTP/1.0 403 Forbidden");
        exit();
    }

    private function BadRequestError()
    {
        header("HTTP/1.0 400 Bad Request");
        exit();
    }
}

$reqest = new userAdmin();
$reqest->indexAction();