<?php
    require_once "inc/cors.php";
    require_once "inc/autoloader.php";

    header("Content-Type: application/json");
    $data = json_decode(file_get_contents('php://input'), true);
    $method = $_SERVER["REQUEST_METHOD"];

    if ($method === "POST") {
        $firstname = ucfirst($data["firstname"]);
        $lastname = ucfirst($data["lastname"]);
        $email = $data["email"];
        $tel = $data["tel"];
        $pass_word = $data["password"];
        
        if (str_contains($firstname, " ")) {
            $firstname = str_replace(" ", "", $firstname);
        }
        if (str_contains($lastname, " ")) {
            $lastname = str_replace(" ", "", $lastname);
        }

        $signup = new Signup();
        echo json_encode($signup->getSignup($firstname, $lastname, $email, $tel, $pass_word));
    }