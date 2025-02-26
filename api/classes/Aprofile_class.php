<?php
    require_once './cors.php';
    // require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Dbh_class.php';

    class Aprofile extends Signup {
        public $code = "400";
        public $success = "200";

        public function setMessage($code, $note) {
            $call_note = [
                "code"=>$code,
                "note"=>$note
            ];
            return $call_note;
        }

        public function getProfile($code, $id) {
            $sql = "";
            $auth = "";
            if ($code === "self") {
                $auth = $id;
                $sql = "SELECT * FROM users WHERE token=?";
            }
            else if ($code === "user") {
                $auth = (int)$id / 36546;
                $sql = "SELECT * FROM users WHERE id=?";
            }

            //let's query database to get all user profiles
            $stmt = $this->con()->prepare($sql);
            $stmt->execute([$auth]);
            $result = $stmt->fetchAll();
            $count_result = count($result);

            $all_user = [];

            if ($count_result <= 0) {
                $code = "400";

                $data = [
                    "code"=>$code,
                    "note"=>false
                ];
                array_push($all_user, $data);
                return $all_user;
            }
            else {
                foreach($result as $user) {
                    $email = $user["email"];
                    $firstname = $user["firstname"];
                    $lastname = $user["lastname"];
                    $nickname = $user["nickname"];
                    $lastclass = $user["lastclass"];
                    $house = $user["house"];
                    $tel = $user["tel"];
                    $img = $user["img"];

                    $data = [
                        "code"=>"200",
                        "email"=>$email,
                        "firstname"=>$firstname,
                        "lastname"=>$lastname,
                        "nickname"=>$nickname,
                        "lastclass"=>$lastclass,
                        "house"=>$house,
                        "tel"=>$tel,
                        "img"=>$img
                    ];
                    array_push($all_user, $data);
                }
                return $all_user;
            }
        }
    }