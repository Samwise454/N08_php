<?php
    require_once "inc/cors.php";
    require_once "inc/autoloader.php";
    require_once "inc/session_config.php";

    header("Content-Type: application/json");
    $data = json_decode(file_get_contents('php://input'), true);
    $method = $_SERVER["REQUEST_METHOD"];

    if ($method === "POST") {
        $email = $data["email"];
        $pass_word = $data["password"];

        echo $email;
        exit();
        
        $login = new Login();
        $login_data = $login->getLogin($email, $pass_word);
        
        if ($login_data["code"] === "error 401") {
            $data = [
                "message"=>"Bad input",//bad password
                "status"=>"error 401"
            ];
            echo json_encode($data);
            exit();
        }
        else if ($login_data["code"] === "error 403") {
            $data = [
                "message"=>"Empty field",
                "status"=>"error 403"
            ];
            echo json_encode($data);
            exit();
        }
        else if ($login_data["code"] === "error 310") {
            $data = [
                "message"=>"Invalid input",//no user
                "status"=>"error 310"
            ];
            echo json_encode($data);
            exit();
        }
        else if ($login_data["code"] === "200") {
            $data = [
                "message"=>$login_data["username"],
                "id"=>$login_data["id"],
                "status"=>"200"
            ];
            echo json_encode($data);
            $_SESSION["user"] = $login_data["username"];
            exit();
        }
    }