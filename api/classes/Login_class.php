<?php
    require_once './cors.php';
    // require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Signup_class.php';

    class Login extends Signup {
        public $not_found = "404";
        public $fatal_error = "500";
        public $bad_email = "400";//bad email
        public $bad_password = "401";//bad password
        public $empty_data = "403";//empty field
        public $no_user = "310";
        public $success = "200";

        public function getLogin($email, $password) {
            if (empty($email) || empty($password)) {
               $data = [
                    "code"=>$this->empty_data,
                    "id"=>"",
                    "username"=>""
                ];
                return $data;
            }
            else {
                //we check for user existence
                $sql = "SELECT * FROM signup WHERE email=?;";
                $stmt = $this->con()->prepare($sql);
                $stmt->execute([$email]);
                $result = $stmt->fetchAll();
                $count_result = count($result);

                if ($count_result <= 0) {
                    $data = [
                        "code"=>$this->no_user,
                        "id"=>"",
                        "username"=>""
                    ];
                    return $data;
                }
                else {
                    //we check for password
                    $hash = $result[0]["pass_word"];
                    $check_password = password_verify($password, $hash);

                    if ($check_password) {
                        // return $email;//use to start session
                        $data = [
                            "code"=>$this->success,
                            "id"=>$result[0]["id"],
                            "username"=>$result[0]["lastname"]
                        ];
                        return $data;//take data apart and use to start session
                    }
                    else {
                        $data = [
                            "code"=>$this->bad_password,
                            "id"=>"",
                            "username"=>""
                        ];
                        return $data;
                    }
                }
            }
        }
    }


    // {
    //     "firstname": "Emmanuel",
    //     "lastname": "Pence",
    //     "email": "emmapence@yahoo.com",
    //     "tel": "08101802021",
    //     "pass_word": "Mecuri12$"
    // }