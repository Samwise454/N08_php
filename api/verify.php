<?php
    require_once "inc/cors.php";
    require_once "inc/autoloader.php";
    require_once "inc/session_config.php";

    header("Content-Type: application/json");
    // $data = json_decode(file_get_contents('php://input'), true);
    $method = $_SERVER["REQUEST_METHOD"];

    if ($method === "GET") {
        // $data = $data["verify"];
        $logged = [
            "message"=>$_SESSION["user"],
            "status"=>"200"
        ];

        $not_logged = [
            "message"=>"Not logged in!",
            "status"=>"201"
        ];

        // if ($data === "verify") {
            if (isset($_SESSION["user"])) {
                echo json_encode($logged);
                exit();
            }
            else {
                echo json_encode($not_logged);
                exit();
            }
        // }
        // else {
        //     echo json_encode($not_logged);
        //     exit();
        // }
    }