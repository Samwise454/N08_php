<?php
    require_once "cors.php";
    require_once "autoloader.php";
    require_once "session_config.php";

    header("Content-Type: application/json");
    $data = json_decode(file_get_contents('php://input'), true);
    $method = $_SERVER["REQUEST_METHOD"];

    if ($method === "POST") {
        $email = $data["email"];
        $pass_word = $data["password"];

        $login = new Login();
        echo json_encode($login->getLogin($email, $pass_word));
    }