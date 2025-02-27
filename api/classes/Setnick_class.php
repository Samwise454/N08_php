<?php
    require_once './cors.php';
    // require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Dbh_class.php';

    class Setnick extends Signup {
        public $code = "400";
        public $success = "200";

        public function setMessage($code, $note) {
            $call_note = [
                "code"=>$code,
                "note"=>$note
            ];
            return $call_note;
        }

        public function setNick($auth, $nickname) {
            if (empty($nickname)) {
                $note = "Input cannot be empty!";
                return $this->setMessage($this->code, $note);
            }
            else if (empty($auth)) {
                $note = "Error processing!";
                return $this->setMessage($this->code, $note);
            }
            else if (!preg_match("/^[a-zA-Z0-9-]*$/", $nickname)) {
                $note = "Check nickname for invalid characters";
                return $this->setMessage($this->code, $note);
            }
            else {
                //verify user with auth
                $sql = "SELECT * FROM users WHERE token=?;";
                $stmt = $this->con()->prepare($sql);
                $stmt->execute([$auth]);
                $result = $stmt->fetchAll();
                $count_result = count($result);

                if ($count_result > 0) {
                    //user exists, we update
                    $sql = "UPDATE users SET nickname=? WHERE token=?;";
                    $stmt = $this->con()->prepare($sql);
                    $stmt->execute([$nickname, $auth]);

                    $note = "Nickname updated!";
                    return $this->setMessage($this->success, $note);
                }
                else {
                    $note = "Error processing!";
                    return $this->setMessage($this->code, $note);
                }
            }
        }
    }