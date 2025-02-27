<?php
    require_once "cors.php";
    require_once "autoloader.php";
    require_once "session_config.php";

    header("Content-Type: application/json");
    $headers = apache_request_headers();
    $data = json_decode(file_get_contents('php://input'), true);
    $method = $_SERVER["REQUEST_METHOD"];

    if ($method === "POST") {
       //we search desriptions for matching data 
        $keyword = $data["search"];

        $check_auth = new Getbiz();
        echo json_encode($check_auth->searchBiz($keyword));
    }