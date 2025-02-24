<?php
    require_once './cors.php';
    // require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Dbh_class.php';

    class Signup extends Dbh {
        public $not_found = "error 404";
        public $fatal_error = "error 500";
        public $bad_name = "error 300";//bad name
        public $bad_email = "error 400";//bad email
        public $bad_tel = "error 402";//bad tel
        public $bad_password = "error 401";//bad password
        public $empty = "error 403";//empty field
        public $user_exist = "error 320";
        public $success = "200";

        public function setMessage($message, $error) {
            $code = [
                "message"=>$message,
                "status"=>$error
            ];
            return $code;
        }

        public function getSignup($firstname, $lastname, $email, $tel, $pass_word) {
            if (empty($firstname) || empty($lastname) || empty($tel) || empty($email) || empty($pass_word)) {
                return $this->setMessage("Check for empty field(s)", $this->empty);
            }
            else if (!preg_match("/^[a-zA-Z]*$/", $firstname) || !preg_match("/^[a-zA-Z]*$/", $lastname)) {
                return $this->setMessage("Invalid name(s)", $this->bad_name);
            }
            else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->setMessage("Invalid email", $this->bad_email);
            }
            else if (mb_strlen($tel) > 14) {
                return $this->setMessage("Invalid tel", $this->bad_tel);
            }
            else if (mb_strlen($pass_word) < 6) {
                return $this->setMessage("Password must be above 6 characters!", $this->bad_password);
            }
            else {
                //we check whether email has been used before 
                $sql = "SELECT * FROM signup WHERE email=?;";
                $stmt = $this->con()->prepare($sql);
                $stmt->execute([$email]);
                $result = $stmt->fetchAll();
                $count_result = count($result);

                if ($count_result > 0) {
                    //email exists
                    return $this->setMessage("User exists, login", $this->user_exist);
                }
                else {
                    //we insert 
                    //let's hash the password 
                    $password = password_hash($pass_word, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO signup (firstname, lastname, email, tel, pass_word) VALUES (?,?,?,?,?);";
                    $stmt = $this->con()->prepare($sql);
                    $stmt->execute([$firstname, $lastname, $email, $tel, $password]);
                    return $this->setMessage("Signup successful", $this->success);
                }
            }
        }
    }