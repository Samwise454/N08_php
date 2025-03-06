<?php
    require_once './cors.php';
    // require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Dbh_class.php';

    class Setbiz extends Signup {
        public $code = "400";
        public $success = "200";

        public function setMessage($code, $note) {
            $call_note = [
                "code"=>$code,
                "note"=>$note
            ];
            return $call_note;
        }

        public function checkAuth($auth) {
            $sql = "SELECT * FROM users WHERE token=?;";
            $stmt = $this->con()->prepare($sql);
            $stmt->execute([$auth]);
            $result = $stmt->fetchAll();
            $count_result = count($result);

            if ($count_result > 0) {
                return $result;
            }
            else {
                return false;
            }
        }

        public function uploadImg($auth, $formData, $which_image) {
            //therefore we will be uploading image where auth is a match
            //which_image is whether logo or img1 or img2
            $result = $this->checkAuth($auth);

            if ($result === false) {
                return false;
            }
            else {
                //Search bizdata table with email for exisiting file
                $email = $result[0]["email"];
                
                //now for each image, let's select it's old name from bizdata table, so we can delete if exists
                $sql = "SELECT * FROM bizdata WHERE email=?;";
                $stmt = $this->con()->prepare($sql);
                $stmt->execute([$email]);
                $data = $stmt->fetchAll();
                $count_data = count($data);//to check whether data has been added before or not

                if ($count_data > 0) {
                    if (!empty($formData)) {
                        //bizdata exists
                        $old_img = $data[0][$which_image];
                        $old_file = '../images/bizdata/'.$old_img;

                        $allowed_ext = ["png", "jpg", "jpeg", "PNG", "JPG", "JPEG"];
                        $filename = $formData["name"];
                        $mimetype = $formData["type"];
                        $size = $formData["size"];
                        $random_num = rand(0000, 9999);
                        $tmp_path = $formData["tmp_name"];

                        $file_ext = strtolower(explode(".", $filename)[1]);
                        $file_type = strtolower(explode("/", $mimetype)[1]);

                        $originalname = explode(".", $filename)[0];
                        $new_filename = $originalname."_".$random_num.".".$file_ext;
                        $target_path = '../images/bizdata/'.$new_filename;
                        
                        if (!in_array($file_ext, $allowed_ext)) {
                            return "bad_ext";
                        }
                        else if (!in_array($file_type, $allowed_ext)) {
                            return "bad_file";
                        }
                        else if ($size > 2000000) {
                            return "bad_size";
                        }
                        else {
                            //before update image name, let's get the name and delete old image if exists
                            if ($old_img !== '' && $old_img !== "img1.jpg" && $old_img !== "img2.jpg" && $old_img !== "logo.jpg") {
                                //we delete the file that matches the profile
                                unlink($old_file);
                            }

                            //let's update database with new filename
                            $sql = "UPDATE bizdata SET $which_image=? WHERE email=?;";
                            $stmt = $this->con()->prepare($sql);
                            $stmt->execute([$new_filename, $email]);

                            move_uploaded_file($tmp_path, $target_path);
                            return true;
                        }
                    }
                    else {
                        //we are returning true here because it means no image was uploaded
                        return "no_image";
                    }
                }
            }
        }

        public function runSql($bizname, $desc, $email) {
            $sql = '';
            if(!empty($bizname) && empty($desc)) {
                $sql = "UPDATE bizdata SET bizname=? WHERE email=?;";
                $stmt = $this->con()->prepare($sql);
                $stmt->execute([$bizname, $email]);
            }
            else if (empty($biname) && !empty($desc)) {
                $sql = "UPDATE bizdata SET bizdesc=? WHERE email=?;";
                $stmt = $this->con()->prepare($sql);
                $stmt->execute([$desc, $email]);
            }
            else if (!empty($biname) && !empty($desc)) {
                $sql = "UPDATE bizdata SET bizname=?, bizdesc=? WHERE email=?;";
                $stmt = $this->con()->prepare($sql);
                $stmt->execute([$bizname, $desc, $email]);
            }
            return true;
        }

        public function setBizData($auth, $bizname, $desc) {
            if (empty($auth)) {
                $note = "Error processing";
                return $this->setMessage($this->code, $note);
            }
            else if (!empty($desc) && mb_strlen($desc) > 600) {
                $note = "Description too long! (max 600)";
                return $this->setMessage($this->code, $note);
            }
            else {
                // let's check whether auth is legit
                $check_auth = $this->checkAuth($auth);//result from checking token
                if ($check_auth === false) {
                    //auth not found or not legit
                    $note = "Error processing2";
                    return $this->setMessage($this->code, $note);
                }
                else {
                    //legit auth, there we insert into biz table 
                    //let's fetch the email address from the users table 
                    $email = $check_auth[0]["email"];

                    //now we search bizdata table for this email, to either update or insert
                    $sql = "SELECT * FROM bizdata WHERE email=?;";
                    $stmt = $this->con()->prepare($sql);
                    $stmt->execute([$email]);
                    $result = $stmt->fetchAll();
                    $count_result = count($result);

                    if ($count_result > 0) {
                        //means data has been added before therefore we update
                        
                        $bizdata_update = $this->runSql($bizname, $desc, $email);

                        if ($bizdata_update == true) {
                            $note = "Done";
                            return $this->setMessage($this->success, $note);
                        }
                    }
                    else {
                        //we insert
                        $logo = "";
                        $img1 = "";
                        $img2 = "";
                        $social = "";//social media link

                        $sql = "INSERT INTO bizdata (email, bizname, bizdesc, bizlogo, bizimg1, bizimg2, social) VALUES (?,?,?,?,?,?,?);";
                        $stmt = $this->con()->prepare($sql);
                        $stmt->execute([$email, $bizname, $desc, $logo, $img1, $img2, $social]);

                        $note = "Done";
                        return $this->setMessage($this->success, $note);
                    }
                }
            }
        }

        public function setBizImg($auth, $logo, $img1, $img2) {
            //now we want to upload these 3 images logo, img1 and img2
            if (empty($auth)) {
                $note = "Error processing!";
                return $this->setMessage($this->code, $note);
            }
            else if ($this->checkAuth($auth) === false) {
                $note = "Error processing!";
                return $this->setMessage($this->code, $note);
            }
            else {
                $logo_upload = $this->uploadImg($auth, $logo, "bizlogo");
                $img1_upload = $this->uploadImg($auth, $img1, "bizimg1");
                $img2_upload = $this->uploadImg($auth, $img2, "bizimg2");

                if ($logo_upload === "bad_ext" || $img1_upload === "bad_ext" || $img2_upload === "bad_ext") {
                    $note = "Invalid file (jpg, png and jpeg)";
                    return $this->setMessage($this->code, $note);
                }
                else if ($logo_upload === "bad_file" || $img1_upload === "bad_file" || $img2_upload === "bad_file") {
                    $note = "Invalid file (jpg, png and jpeg)";
                    return $this->setMessage($this->code, $note);
                }
                else if ($logo_upload === "bad_size" || $img1_upload === "bad_size" || $img2_upload === "bad_size") {
                    $note = "File should be <= 2mb";
                    return $this->setMessage($this->code, $note);
                }
                else if ($logo_upload === "no_image" && $img1_upload === "no_image" && $img2_upload === "no_image") {
                    $note = "Data updated but No image uploaded";
                    return $this->setMessage($this->success, $note);
                }
                else if ($logo_upload === true && $img1_upload === true && $img2_upload === true) {
                    $note = "Data Updated!";
                    return $this->setMessage($this->success, $note);
                }
                else {

                    $note = "Error processing";
                    return $this->setMessage($this->code, $note);
                }
            }
        }
    }