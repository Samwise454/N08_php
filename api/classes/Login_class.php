<?php
    require_once './cors.php';
    // require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Signup_class.php';

    class Login extends Signup {
        public $code = "400";
        public $success = "200";

        public function callback($note, $code, $token) {
            $call_note = [
                "code"=>$code,
                "note"=>$note,
                "token"=>$token
            ];
            return $call_note;
        }

        public function getLogin($email, $password) {
            if (empty($email) || empty($password)) {
                $note = "Check for empty input";
                $token = "";
                return $this->callback($note, $this->code, $token);
            }
            else {
                //we check for user existence
                $sql = "SELECT * FROM users WHERE email=?;";
                $stmt = $this->con()->prepare($sql);
                $stmt->execute([$email]);
                $result = $stmt->fetchAll();
                $count_result = count($result);

                if ($count_result <= 0) {
                    $note = "Invalid email or password!";
                    $token = "";
                    return $this->callback($note, $this->code, $token);
                }
                else {
                    //we check for password
                    $hash = $result[0]["password"];
                    $check_password = password_verify($password, $hash);

                    $token = $result[0]["token"];

                    if ($check_password === false) {
                        //password check wrong
                        $note = "Invalid email or password!";
                        $token = "";
                        return $this->callback($note, $this->code, $token);
                    }
                    else if ($check_password === true) {
                        //password check wrong
                        $note = "Invalid email or password!";
                        $token = $token;
                        return $this->callback($note, $this->success, $token);
                    }
                }
            }
        }
    }