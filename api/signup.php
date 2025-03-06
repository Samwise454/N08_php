<?php
    require_once "cors.php";
    require_once "autoloader.php";

    header("Content-Type: application/json");
    $data = json_decode(file_get_contents('php://input'), true);
    $method = $_SERVER["REQUEST_METHOD"];

    if ($method === "POST") {
        $firstname = ucfirst($data["firstname"]);
        $lastname = ucfirst($data["lastname"]);
        $nickname = ucfirst($data["nickname"]);
        $email = $data["email"];
        $tel = $data["tel"];
        $quest = $data["quest"];
        $lastclass = ucfirst($data["lastclass"]);
        $house = ucfirst($data["house"]);
        $pass_word = $data["password"];
        $confirm_password = $data["confirm_pass"];
        $img = ucfirst($data["img"]);

        $signup = new Signup();
        echo json_encode($signup->getSignup($firstname, $lastname, $nickname, $email, $tel, $quest, $lastclass, $house, $pass_word, $confirm_password, $img));
    }