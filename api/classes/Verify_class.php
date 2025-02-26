<?php
    require_once './cors.php';
    // require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Dbh_class.php';

    class Verify extends Signup {
        public $code = "400";
        public $success = "200";

        public function setMessage($code, $note) {
            $call_note = [
                "code"=>$code,
                "note"=>$note
            ];
            return $call_note;
        }

        public function verifyUser($auth) {
            if (mb_strlen($auth) !== 64) {
                $code = "400";
                $note = false;
                return $this->setMessage($code, $note);
            }
            else {
                //let's query database to check existence of the cookie
                $sql = "SELECT * FROM users WHERE token=?";
                $stmt = $this->con()->prepare($sql);
                $stmt->execute([$auth]);
                $result = $stmt->fetchAll();
                $count_result = count($result);

                if ($count_result > 0) {
                    $code = "200";
                    $note = true;
                    return $this->setMessage($code, $note);
                }
                else {
                    $code = "400";
                    $note = false;
                    return $this->setMessage($code, $note);
                }
            }
        }
    }