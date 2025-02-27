<?php

class Dbh {
    // private $host = "localhost";//change later
    // private $user = "root";
    // private $password = "";
    // private $db_name = "fgcn08";

    private $host = "localhost";//change later
    private $user = "u211176085_n08";
    private $password = "Mecuri12$";
    private $db_name = "u211176085_fgcn08";

    protected function con() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;
        $pdo = new PDO($dsn, $this->user, $this->password);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    }
}