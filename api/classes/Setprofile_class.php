<?php
    require_once './cors.php';
    // require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Dbh_class.php';

    class Setprofile extends Signup {
        public $code = "400";
        public $success = "200";

        public function setMessage($code, $note) {
            $call_note = [
                "code"=>$code,
                "note"=>$note
            ];
            return $call_note;
        }

        public function setProfile($auth, $formData) {
            if(empty($auth) || empty($formData)) {
                $note = "Error processing";
                return $this->setMessage($this->code, $note);
            }
            else {
                // let's check whether auth is legit
                $sql = "SELECT * FROM users WHERE token=?;";
                $stmt = $this->con()->prepare($sql);
                $stmt->execute([$auth]);
                $result = $stmt->fetchAll();
                $count_result = count($result);

                if ($count_result > 0) {
                    //legit auth 
                    //therefore we will be uploading image where auth is a match
                    $old_img = $result[0]["img"];
                    $old_file = '../images/profile/'.$result[0]["img"];

                    $allowed_ext = ["png", "jpg", "jpeg"];
                    $filename = $formData["name"];
                    $mimetype = $formData["type"];
                    $size = $formData["size"];
                    $random_num = rand(0000, 9999);
                    $tmp_path = $formData["tmp_name"];

                    $file_ext = explode(".", $filename)[1];
                    $file_type = explode("/", $mimetype)[1];

                    $originalname = explode(".", $filename)[0];
                    $new_filename = $originalname."_".$random_num.".".$file_ext;
                    $target_path = '../images/profile/'.$new_filename;
                    
                    if (!in_array($file_ext, $allowed_ext)) {
                        $note = "Invalid file (jpg, png and jpeg)";
                        return $this->setMessage($this->code, $note);
                    }
                    else if (!in_array($file_type, $allowed_ext)) {
                        $note = "Invalid file (jpg, png and jpeg)";
                        return $this->setMessage($this->code, $note);
                    }
                    else if ($size > 2000000) {
                        $note = "File should be <= 2mb";
                        return $this->setMessage($this->code, $note);
                    }
                    else {
                        //before update image name, let's get the name and delete old image if exists
                        if ($old_img !== 'profile.jpg') {
                            //we delete the file that matches the profile
                            unlink($old_file);
                        }

                        //let's update database with new filename
                        $sql = "UPDATE users SET img=? WHERE token=?;";
                        $stmt = $this->con()->prepare($sql);
                        $stmt->execute([$new_filename, $auth]);

                        move_uploaded_file($tmp_path, $target_path);
                        $note = "Upload Successful!";
                        return $this->setMessage($this->success, $note);
                    }
                }
                else {
                    $note = "Error processing";
                    return $this->setMessage($this->code, $note);
                }
            }
        }
    }