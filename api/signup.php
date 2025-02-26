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
        $lastclass = ucfirst($data["lastclass"]);
        $house = ucfirst($data["house"]);
        $pass_word = $data["password"];
        $img = ucfirst($data["img"]);
        
        if (str_contains($firstname, " ")) {
            $firstname = str_replace(" ", "", $firstname);
        }
        else if (str_contains($lastname, " ")) {
            $lastname = str_replace(" ", "", $lastname);
        }
        else if (str_contains($nickname, " ")) {
            $nickname = str_replace(" ", "", $nickname);
        }

        $signup = new Signup();
        echo json_encode($signup->getSignup($firstname, $lastname, $nickname, $email, $tel, $lastclass, $house, $pass_word, $img));
    }