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
        $code = $auth[0];
        $id = $auth[1];

        $get_profile = new Aprofile();
        echo json_encode($get_profile->getProfile($code, $id));
    }