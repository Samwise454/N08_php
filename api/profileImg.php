<?php
    require_once "cors.php";
    require_once "autoloader.php";
    require_once "session_config.php";

    // header("Content-Type: application/json");
    // $headers = apache_request_headers();
    // $data = json_decode(file_get_contents('php://input'));
    // $method = $_SERVER["REQUEST_METHOD"];

    // if ($method === "POST") {
    //     $auth = explode(" ", $headers["Authorization"])[1];
    //     $formData = $_FILES["profile"];
        
    //     $get_profile = new Setprofile();
    //     echo json_encode($get_profile->setProfile($auth, $formData));
    // }

    header("Content-Type: application/json");
    $headers = apache_request_headers();
    $data = json_decode(file_get_contents('php://input'));
    $method = $_SERVER["REQUEST_METHOD"];


    if ($method === "POST") {
        $auth = explode(" ", $headers["Authorization"])[1];
        $formUrl = (array)$data;
        $image_data = $formUrl["image"];//this is a base 64 string
        $image = explode(",", $image_data)[1];//this will give us the actual base 64 file
        $base64 = base64_decode($image);
        
        $get_profile = new Setprofile();
        echo json_encode($get_profile->setProfile($auth, $base64));
    }