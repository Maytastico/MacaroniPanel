<?php
require "../_includes/autoloader.inc.php";

class userAdmin extends ApiException
{
    public function indexAction(){

        try{
            $input = json_decode(file_get_contents('php://input'), true);
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                $result = $this->get();
            }elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $result = $this->create($_POST);
            } elseif ($_SERVER['REQUEST_METHOD'] == 'PUT'){
                $result = $this->update($input);
            }elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE'){
                $result = $this->delete($input);
            }

        }catch (ApiException $e){
            if($e->getCode() == ApiException::MALFORMED_INPUT){
                header('HTTP/1.0 409 Bad Request');
            }
        }
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type");
        echo  json_encode($result);
    }

    private function get(){
        $userObj = User::getUserTable();
        $response = null;
        $i=0;
        foreach ($userObj as $user){
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

    private function create($input){
        return $input;
    }

    private function update($data){
        return $data;
    }

    private function delete($data){
        return array("success" => "poor user");
    }
}

$reqest = new userAdmin();
$reqest->indexAction();