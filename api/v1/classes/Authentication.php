<?php
require_once "Db.php";

class Authentication extends Db{
    public function check_user($username,$password){
        $sql = "SELECT * FROM users WHERE username =? AND password=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$username,$password]);
        $num = $stmt->rowCount();
        if($num > 0){
            return true;
        }else{
            return false;
        }
    }
}

// $auth = new Authentication;
// $res = $auth->check_user("Abie","recipe@PLAN");
// var_dump($res);
