<?php
    require_once './cors.php';
    // require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Dbh_class.php';

    class Signup extends Dbh {
        public $code = "400";
        public $success = "200";

        public function setMessage($code, $note) {
            $call_note = [
                "code"=>$code,
                "note"=>$note
            ];
            return $call_note;
        }

        public function check_tel($tel) {
            if (!preg_match("/^[0-9+]*$/", $tel)) {
                return false;
            }
            else if (str_contains($tel, '+234') && mb_strlen($tel) !== 14) {
                return false;
            }
            else if (mb_strlen($tel) > 14) {
                return false;
            }
            else {
                return true;
            }
        }
        
        public function getSignup($firstname, $lastname, $nickname, $email, $tel, $lastclass, $house, $pass_word, $img) {
            $lastclass_array = ["Blue", "Green", "Purple", "Violet", "White", "Yellow"];
            $house_array = ["Anambra", "Benue", "Imo", "Niger"];
            $img = 'profile.jpg';
            $token = bin2hex(openssl_random_pseudo_bytes(32));

            if (empty($firstname) || empty($lastname) || empty($nickname) || empty($email) || empty($tel) || empty($lastclass) || empty($house) || empty($pass_word)) {
                return $this->setMessage($this->code, "Check for empty field(s)");
            }
            else if (!preg_match("/^[a-zA-Z]*$/", $firstname) || !preg_match("/^[a-zA-Z]*$/", $lastname) || !preg_match("/^[a-zA-Z0-9]*$/", $nickname)) {
                return $this->setMessage($this->code, "Invalid name(s)");
            }
            else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->setMessage($this->code, "Invalid email address");
            }
            else if ($this->check_tel($tel) == false) {
                return $this->setMessage($this->code, "Invalid phone number!");
            }
            else if (!in_array($lastclass, $lastclass_array)) {
                return $this->setMessage($this->code, "Invalid class!");
            }
            else if (!in_array($house, $house_array)) {
                return $this->setMessage($this->code, "Invalid house!");
            }
            else if (mb_strlen($pass_word) < 6) {
                return $this->setMessage($this->code, "Password must be at least 6 characters!");
            }
            else {
                //we check whether email, tel or nickname has been used before 
                $sql = "SELECT * FROM users WHERE email=? AND tel=? AND nickname=?;";
                $stmt = $this->con()->prepare($sql);
                $stmt->execute([$email, $tel, $nickname]);
                $result = $stmt->fetchAll();
                $count_result = count($result);

                if ($count_result > 0) {
                    //data exists
                    return $this->setMessage($this->success, "Account exists, login!");
                }
                else {
                    //we insert 
                    //let's hash the password 
                    $password = password_hash($pass_word, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO users (firstname, lastname, nickname, email, tel, lastclass, house, password, img, token) VALUES (?,?,?,?,?,?,?,?,?,?);";
                    $stmt = $this->con()->prepare($sql);
                    $stmt->execute([$firstname, $lastname, $nickname, $email, $tel, $lastclass, $house, $password, $img, $token]);
                    return $this->setMessage($this->success, "Signup Successful!");
                }
            }
        }
    }