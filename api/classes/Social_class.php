<?php
    require_once './cors.php';
    // require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Dbh_class.php';

    class Social extends Signup {
        public $code = "400";
        public $success = "200";

        public function setMessage($code, $note) {
            $call_note = [
                "code"=>$code,
                "note"=>$note
            ];
            return $call_note;
        }

        public function checkLinks($link) {
            if (!filter_var($link, FILTER_VALIDATE_URL)) {
                return false;
            }
            else {
                return true;
            }
        }

        public function addSocial($column, $link, $auth) {
            $sql = "UPDATE users SET $column=? WHERE token=?;";
            $stmt = $this->con()->prepare($sql);
            
            if ($stmt->execute([$link, $auth])) {
                return true;
            }
            else {
                return false;
            }
        }
        
        public function setSocial($twitter, $insta, $facebook, $linkedin, $youtube, $auth) {
            // $social_array = [$twitter, $insta, $facebook, $linkedin, $youtube];
            if (empty($auth)) {
                return $this->setMessage($this->code, "Error processing");
            }
            else if (empty($twitter) && empty($insta) && empty($facebook) && empty($linkedin) && empty($youtube)) {
                return $this->setMessage($this->code, "Field cannot be empty!");
            }
            else if (!empty($twitter) && $this->checkLinks($twitter) == false) {
                return $this->setMessage($this->code, "Invalid link!");
            }
            else if (!empty($insta) && $this->checkLinks($insta) == false) {
                return $this->setMessage($this->code, "Invalid link!");
            }
            else if (!empty($facebook) && $this->checkLinks($facebook) == false) {
                return $this->setMessage($this->code, "Invalid link!");
            }
            else if (!empty($linkedin) && $this->checkLinks($linkedin) == false) {
                return $this->setMessage($this->code, "Invalid link!");
            }
            else if (!empty($youtube) && $this->checkLinks($youtube) == false) {
                return $this->setMessage($this->code, "Invalid link!");
            }
            else {
                // we update social for each link 
                if (!empty($twitter)) {
                    $column = "twitter";
                    $set_social = $this->addSocial($column, $twitter, $auth);
                    if ($set_social == true) {
                        return $this->setMessage($this->success, "Twitter Link Set");
                    }
                    else {
                        return $this->setMessage($this->code, "Error processing!");
                    }
                }
                else if (!empty($insta)) {
                    $column = "insta";
                    $set_social = $this->addSocial($column, $insta, $auth);
                    if ($set_social == true) {
                        return $this->setMessage($this->success, "Instagram Link Set");
                    }
                    else {
                        return $this->setMessage($this->code, "Error processing!");
                    }
                }
                else if (!empty($facebook)) {
                    $column = "facebook";
                    $set_social = $this->addSocial($column, $facebook, $auth);
                    if ($set_social == true) {
                        return $this->setMessage($this->success, "Facebook Link Set");
                    }
                    else {
                        return $this->setMessage($this->code, "Error processing!");
                    }
                }
                else if (!empty($linkedin)) {
                    $column = "linkedin";
                    $set_social = $this->addSocial($column, $linkedin, $auth);
                    if ($set_social == true) {
                        return $this->setMessage($this->success, "Linkedin Link Set");
                    }
                    else {
                        return $this->setMessage($this->code, "Error processing!");
                    }
                }
                else if (!empty($youtube)) {
                    $column = "youtube";
                    $set_social = $this->addSocial($column, $youtube, $auth);
                    if ($set_social == true) {
                        return $this->setMessage($this->success, "Youtube Link Set");
                    }
                    else {
                        return $this->setMessage($this->code, "Error processing!");
                    }
                }
            }
        }          
    }