<?php

class ProfileDataHandler {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function validateEmail($em) {
        if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    public function fetchProfileData($un) {
        $query = $this->con->prepare("SELECT name, username, admission, profilepic FROM users WHERE username=:un");
        $query->bindParam(":un", $un);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);
        $data['profilepic'] = 'http://192.168.43.25/api/'.$data['profilepic'];
        return json_encode($data);
    }
}
