<?php
    require_once "cors.php";
    require_once "autoloader.php";
    require_once "session_config.php";

    header("Content-Type: application/json");
    $headers = apache_request_headers();
    $data = json_decode(file_get_contents('php://input'), true);
    $method = $_SERVER["REQUEST_METHOD"];

    // if ($method === "POST") {
    //     $auth = explode(" ", $headers["Authorization"])[1];

    //     if (isset($FILES["logo"])) {
    //         $logo = $_FILES["logo"];
    //     }
    //     else {
    //         $logo = "";
    //     }

    //     if (isset($_FILES["img1"])) {
    //         $img1 = $_FILES["img1"];
    //     }
    //     else {
    //         $img1 = "";
    //     }
        
    //     if (isset($_FILES["img2"])) {
    //         $img2 = $_FILES["img2"];
    //     }
    //     else {
    //         $img2 = "";
    //     }
        
    //     $get_profile = new Setbiz();
    //     echo json_encode($get_profile->setBizImg($auth, $logo, $img1, $img2));
    // }

    if ($method === "POST") {
        $auth_data = explode(" ", $headers["Authorization"]);
        $column = $auth_data[0];//eg bizlogo or bizimg1 (table column name);//IMPORTANT
        $auth = $auth_data[1];//IMPORTANT
        $formUrl = (array)$data;
        $image_data = $formUrl["image"];//this is a base 64 string
        $image = explode(",", $image_data)[1];//this will give us the actual base 64 file
        $base64 = base64_decode($image);//IMPORTANT

        $set_biz = new Setbiz();
        echo json_encode($set_biz->setBizImg($auth, $column, $base64));
    }