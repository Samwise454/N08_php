<?php
    require_once './cors.php';
    // require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Dbh_class.php';

    class Someprofile extends Signup {
        public $code = "400";
        public $success = "200";

        public function getProfile($main, $sub) {
            //main is like class, house, brand or general
            //sub is like house color or class color
            $sql = "";

            $all_user = [];

            $main_array = ["class", "house", "brand", "general"];
            $sub_array = ["Anambra", "Benue", "Imo", "Niger", "Blue", "Green", "Purple", "Violet", "White", "Yellow", "logos", "events"];

            if (!in_array($main, $main_array) || !in_array($sub, $sub_array)) {
                $data = [
                    "code"=>"400"
                ];
                array_push($all_user, $data);
                return $all_user;
            }
            else if ($main === "class") {
                $sql = "SELECT * FROM users WHERE lastclass=?;";
            }
            else if ($main === "house") {
                $sql = "SELECT * FROM users WHERE house=?;";
            }
            else if ($main === "brand") {
                $sql = "SELECT * FROM bizdata;";
            }
            else if ($main === "general") {
                $sql = "SELECT * FROM users;";
            }

            //let's query database to get some user profiles
            $stmt = $this->con()->prepare($sql);
            if ($main === "class" || $main === "house") {
                $stmt->execute([$sub]);
            }
            else {
                $stmt->execute();
            }
            $result = $stmt->fetchAll();
            $count_result = count($result);

            if ($count_result <= 0) {
                $data = [
                    "code"=>$this->code
                ];
                array_push($all_user, $data);
                return $all_user;
            }
            else {
                foreach($result as $user) {
                    $data = [];
                    $email = $user["email"];

                    if ($main === "class" || $main === "house" || $main === "general") {
                        $id = (int)$user["id"] * 36546;
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
                            "id"=>$id,
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
                    else if ($main === "brand") {
                        // $email = $user["email"];

                        //using the email, let's fetch the id
                        $sql = "SELECT * FROM users WHERE email=?;";
                        $stmt = $this->con()->prepare($sql);
                        $stmt->execute([$email]);
                        $user_data = $stmt->fetchAll();

                        foreach($user_data as $use) {
                            $id = (int)$use["id"] * 36546;
                            $bizname = $user["bizname"];
                            $img = $user["bizlogo"];

                            if (mb_strlen($bizname > 12)) {
                                $bizname = substr($bizname, 0, 12) . '...';
                            }
    
                            $data = [
                                "code"=>"200",
                                "id"=>$id,
                                "bizname"=>$bizname,
                                "img"=>$img
                            ];
                        }
                        array_push($all_user, $data);
                    }
                }
                $shuffled_array = $this->shuffleArray($all_user);
                return $shuffled_array;
            }
        }
    }