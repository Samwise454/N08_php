<?php
    require_once './cors.php';
    // require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Dbh_class.php';

    class Getbiz extends Signup {
        public $code = "400";
        public $success = "200";

        public function getBiz($profile_type, $id) {
            $all_data = [];

            if (empty($profile_type) || empty($id)) {
                $code = $this->code;
                $note = "Error processing";
                $data = [
                    "code"=>$code,
                    "note"=>$note
                ];
                array_push($all_data, $data);

                return $all_data;
            }
            else {
                if ($profile_type == "self") {
                    $column = "token";
                }
                else if ($profile_type == "user") {
                    $column = "id";
                    $id = (int)$id / 36546;
                }

                //verify user with auth or id
                $sql = "SELECT * FROM users WHERE $column=?;";
                $stmt = $this->con()->prepare($sql);
                $stmt->execute([$id]);
                $result = $stmt->fetchAll();
                $count_result = count($result);

                if ($count_result > 0) {
                    //user exists, we use email to query bizdata table
                    $email = $result[0]["email"];
                    $tel = $result[0]["tel"];
                    
                    $sql = "SELECT * FROM bizdata WHERE email=?;";
                    $stmt = $this->con()->prepare($sql);
                    $stmt->execute([$email]);
                    $result = $stmt->fetchAll();
                    $count_result = count($result);

                    if ($count_result > 0) {
                        //it exists
                        $bizname = $result[0]["bizname"];
                        $desc = $result[0]["bizdesc"];
                        $logo = $result[0]["bizlogo"];
                        $img1 = $result[0]["bizimg1"];
                        $img2 = $result[0]["bizimg2"];

                        $data = [
                            "bizname"=>$bizname,
                            "desc"=>$desc,
                            "tel"=>$tel,
                            "logo"=>$logo,
                            "img1"=>$img1,
                            "img2"=>$img2
                        ];
                        array_push($all_data, $data);
                        return $all_data;
                    }
                    else {
                        $code = $this->code;
                        $note = "No data";
                        $data = [
                            "code"=>$code,
                            "note"=>$note
                        ];
                        array_push($all_data, $data);

                        return $all_data;
                    }
                }
                else {
                    $code = $this->code;
                    $note = "Error processing";
                    $data = [
                        "code"=>$code,
                        "note"=>$note
                    ];
                    array_push($all_data, $data);

                    return $all_data;
                }
            }
        }

        public function searchBiz($keyword) {
            //here we search biz data business descriptions for any matching word
            $all_data = [];

            if (empty($keyword)) {
                $note = "Input cannot be empty";
                $data = ["note"=>$note];
                array_push($all_data, $data);
                return $all_data;
            }
            else {
                $keyword = strtolower($keyword);
                $sql = "";
                if ($keyword == "all") {
                    $sql = "SELECT * FROM bizdata;";
                }
                else {
                    $sql = "SELECT * FROM bizdata WHERE bizdesc LIKE '%$keyword%';";
                }
                
                $stmt = $this->con()->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll();
                $count_result = count($result);

                if ($count_result > 0) {
                    //data found
                    foreach($result as $res) {
                        $email = $res["email"];
                        $bizname = $res["bizname"];
                        $desc = $res["bizdesc"];
                        $logo = $res["bizlogo"];

                        //using the email address from bizdata let's fetch phone number from users
                        $sql = "SELECT * FROM users WHERE email=?;";
                        $stmt = $this->con()->prepare($sql);
                        $stmt->execute([$email]);
                        $user_data = $stmt->fetchAll();

                        $tel = $user_data[0]["tel"];
                        $id = (int)$user_data[0]["id"] * 36546;
                        $name = $user_data[0]["firstname"]." ".$user_data[0]["lastname"];

                        $data = [
                            "note"=>"200",
                            "name"=>$name,
                            "id"=>$id,
                            "bizname"=>$bizname,
                            "desc"=>$desc,
                            "logo"=>$logo,
                            "tel"=>$tel
                        ];
                        array_push($all_data, $data);
                    }
                    $shuffled_array = $this->shuffleArray($all_data);
                    return $shuffled_array;
                }
                else {
                    $note = "Data not found";
                    $data = ["note"=>$note];
                    array_push($all_data, $data);
                    return $all_data;
                }
            }
        }
    }