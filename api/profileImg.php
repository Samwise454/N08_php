<?php
    require_once "cors.php";
    require_once "autoloader.php";
    require_once "session_config.php";

    header("Content-Type: application/json");
    $headers = apache_request_headers();
    $data = json_decode(file_get_contents('php://input'));
    $method = $_SERVER["REQUEST_METHOD"];

    if ($method === "POST") {
        $auth = explode(" ", $headers["Authorization"])[1];
        $formData = $_FILES["profile"];

        $get_profile = new Setprofile();
        echo json_encode($get_profile->setProfile($auth, $formData));
    }