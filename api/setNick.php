<?php
    require_once "cors.php";
    require_once "autoloader.php";
    require_once "session_config.php";

    header("Content-Type: application/json");
    $headers = apache_request_headers();
    $data = json_decode(file_get_contents('php://input'), true);
    $method = $_SERVER["REQUEST_METHOD"];

    if ($method === "POST") {
        $auth = explode(" ", $headers["Authorization"])[1];
        $nickname = $data["name"];
        
       //we now check the database to see whether the auth exists
        $check_auth = new Setnick();
        echo json_encode($check_auth->setNick($auth, $nickname));
    }