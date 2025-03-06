<?php
    require_once "cors.php";
    require_once "autoloader.php";

    header("Content-Type: application/json");
    $headers = apache_request_headers();
    $data = json_decode(file_get_contents('php://input'), true);
    $method = $_SERVER["REQUEST_METHOD"];

    if ($method === "POST") {
        $twitter = $data["twitter"];
        $insta = $data["insta"];
        $facebook = $data["facebook"];
        $linkedin = $data["linkedin"];
        $youtube = $data["youtube"];

        $auth = explode(" ", $headers["Authorization"])[1];

        $social = new Social();
        echo json_encode($social->setSocial($twitter, $insta, $facebook, $linkedin, $youtube, $auth));
    }