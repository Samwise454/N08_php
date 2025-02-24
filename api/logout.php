<?php
    require_once "inc/cors.php";
    require_once "inc/autoloader.php";
    require_once "inc/session_config.php";

    header("Content-Type: application/json");
    // $data = json_decode(file_get_contents('php://input'), true);
    $method = $_SERVER["REQUEST_METHOD"];


    if ($method === "GET") {
        // $logout = $data["logout"];
        $logged_out = [
            "message"=>"Logged out!",
            "status"=>"200"
        ];

        $logged_in = [
            "message"=>"Not logged out!",
            "status"=>"201"
        ];

        // if ($logout === "logout") {
            $_SESSION = array();
            if (ini_get("session.use_only_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 86400, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
                session_unset();
                session_destroy();
                echo json_encode($logged_out);
                exit();
            }
        // }
        // else {
        //     echo json_encode($logged_in);
        //     exit();
        // }
    }