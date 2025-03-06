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

        public function setProfile($auth, $base64) {
            if(empty($auth) || empty($base64)) {
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
                    $random_num = rand(000000, 999999);
                    $image = imagecreatefromstring($base64);

                    if (!$image) {
                        return "No Image";
                    }
                    else {
                        $image_data = getimagesizefromstring($base64);//this containes image width and height and also mime "image/jpeg"
                        $file_ext = explode("/", $image_data["mime"])[1];// eg jpeg or png
                        $filename = "profile_".$random_num.".png";

                        if (!in_array($file_ext, $allowed_ext)) {
                            $note = "Invalid file (jpg, png and jpeg)";
                            return $this->setMessage($this->code, $note);
                        }
                        else {
                            if ($old_img !== 'profile.jpg') {
                                //we delete the file that matches the profile
                                unlink($old_file);
                            }

                            //let's update database with new filename
                            $sql = "UPDATE users SET img=? WHERE token=?;";
                            $stmt = $this->con()->prepare($sql);
                            $stmt->execute([$filename, $auth]);

                            //let's specify target path, compress the image and upload  
                            $image_file = '../images/profile/'.$filename;
                            imagepng($image, $image_file, 0);

                            $note = "Upload Successful!";
                            return $this->setMessage($this->success, $note);
                        }
                    }   
                }
                else {
                    $note = "Error processing";
                    return $this->setMessage($this->code, $note);
                }
            }
        }
    }