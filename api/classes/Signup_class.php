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

        public function shuffleArray($array) {
            $array_keys = array_keys($array);//collect all array keys
            shuffle($array_keys);
            $shuffled_array = [];
            foreach($array_keys as $key) {
                $shuffled = $array[$key];
                array_push($shuffled_array, $shuffled);
            }
            return $shuffled_array;
        }
        
        public function getSignup($firstname, $lastname, $nickname, $email, $tel, $quest, $lastclass, $house, $pass_word, $confirm_password, $img) {
            $lastclass_array = ["Blue", "Green", "Purple", "Violet", "White", "Yellow"];
            $house_array = ["Anambra", "Benue", "Imo", "Niger"];
            $img = 'profile.jpg';
            $token = bin2hex(openssl_random_pseudo_bytes(32));
            $quest = strtolower($quest);
            $quest_data = "#fgcn08*allow";
            $socials = "-";

            if (empty($firstname) || empty($lastname) || empty($nickname) || empty($email) || empty($tel) || empty($lastclass) || empty($house) || empty($pass_word)) {
                return $this->setMessage($this->code, "Check for empty field(s)");
            }
            else if (!preg_match("/^[a-zA-Z- ]*$/", $firstname) || !preg_match("/^[a-zA-Z- ]*$/", $lastname) || !preg_match("/^[a-zA-Z0-9- ]*$/", $nickname)) {
                return $this->setMessage($this->code, "Invalid name(s)");
            }
            else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->setMessage($this->code, "Invalid email address");
            }
            else if (mb_strlen($tel) > 14) {
                return $this->setMessage($this->code, "Invalid phone number!");
            }
            else if (!preg_match("/^[0-9+]*$/", $tel)) {
                return $this->setMessage($this->code, "Invalid phone number!");
            }
            else if (!str_contains($tel, "+")) {
                return $this->setMessage($this->code, "Add your country code eg [+2348102800000]");
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
            else if ($pass_word !== $confirm_password) {
                return $this->setMessage($this->code, "Password is not a match!");
            }
            else if ($quest !== $quest_data) {
                return $this->setMessage($this->code, "Hmmm, try again with the key!");
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

                    $sql = "INSERT INTO users (firstname, lastname, nickname, email, tel, lastclass, house, password, img, token, twitter, insta, facebook, linkedin, youtube) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
                    $stmt = $this->con()->prepare($sql);
                    $stmt->execute([$firstname, $lastname, $nickname, $email, $tel, $lastclass, $house, $password, $img, $token, $socials, $socials, $socials, $socials, $socials]);

                    $bizname = "-";
                    $desc = "-";
                    $logo = "logo.jpg";
                    $img1 = "img1.jpg";
                    $img2 = "img2.jpg";

                    //let's also insert some default data into biz data
                    $sql = "INSERT INTO bizdata (email, bizname, bizdesc, bizlogo, bizimg1, bizimg2) VALUES (?,?,?,?,?,?);";
                    $stmt = $this->con()->prepare($sql);
                    $stmt->execute([$email, $bizname, $desc, $logo, $img1, $img2]);

                    return $this->setMessage($this->success, "Signup Successful!");
                }
            }
        }
    }