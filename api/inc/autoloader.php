<?php

spl_autoload_register('myAutoLoader');

    function myAutoLoader($className) {
        $path = "classes/";
        $extension = "_class.php";
        $full_path = $path . $className . $extension;

        if (!file_exists($full_path)) {
            return false;
        }

        require_once $full_path;
    }