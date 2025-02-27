<?php
    require_once "cors.php";
    require_once "autoloader.php";
    require_once "session_config.php";

    header("Content-Type: application/json");
    $headers = apache_request_headers();
    $data = json_decode(file_get_contents('php://input'), true);
    $method = $_SERVER["REQUEST_METHOD"];

    if ($method === "GET") {
        $auth = explode(" ", $headers["Authorization"]);
        $profile_type = $auth[0];
        $id = $auth[1];

       //we now check the database to see whether the auth exists
        $check_auth = new Getbiz();
        echo json_encode($check_auth->getBiz($profile_type, $id));
    }