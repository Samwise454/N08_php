<?php
    if (isset($_SERVER["HTTP_ORIGIN"])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Max-Age: 600");
        header("Access-Control-Allow-Methods: POST");
    }