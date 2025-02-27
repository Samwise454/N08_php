<?php
    require_once "cors.php";
    require_once "autoloader.php";
    require_once "session_config.php";

    header("Content-Type: application/json");
    $headers = apache_request_headers();
    $data = json_decode(file_get_contents('php://input'));
    $method = $_SERVER["REQUEST_METHOD"];

    if ($method === "POST") {
        $get_header = explode(" ", $headers["Authorization"]);
        $main = $get_header[0];
        $sub = $get_header[1];

        $get_profile = new Someprofile();
        echo json_encode($get_profile->getProfile($main, $sub));
    }