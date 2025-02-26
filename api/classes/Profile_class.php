<?php
    require_once './cors.php';
    // require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Dbh_class.php';

    class Profile extends Signup {
        public $code = "400";
        public $success = "200";

        public function setMessage($code, $note) {
            $call_note = [
                "code"=>$code,
                "note"=>$note
            ];
            return $call_note;
        }

        public function getProfile() {
            //let's query database to get all user profiles
            $sql = "SELECT * FROM users";
            $stmt = $this->con()->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            $count_result = count($result);

            if ($count_result <= 0) {
                $code = "400";
                $note = false;
                return $this->setMessage($code, $note);
            }
            else {
                $all_user = [];
                foreach($result as $user) {
                    $id = $user["id"] * 36546;
                    $nickname = $user["nickname"];
                    $img = $user["img"];

                    $data = [
                        "code"=>"200",
                        "id"=>$id,
                        "nickname"=>$nickname,
                        "img"=>$img
                    ];
                    array_push($all_user, $data);
                }
                return $all_user;
            }
        }
    }