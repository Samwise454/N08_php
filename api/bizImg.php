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

        if (isset($FILES["logo"])) {
            $logo = $_FILES["logo"];
        }
        else {
            $logo = "";
        }

        if (isset($_FILES["img1"])) {
            $img1 = $_FILES["img1"];
        }
        else {
            $img1 = "";
        }
        
        if (isset($_FILES["img2"])) {
            $img2 = $_FILES["img2"];
        }
        else {
            $img2 = "";
        }
        
        $get_profile = new Setbiz();
        echo json_encode($get_profile->setBizImg($auth, $logo, $img1, $img2));
    }