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

    public function fetchProfileData($em) {
        $query = $this->con->prepare("SELECT name, admission, profilepic FROM users WHERE email=:em");
        $query->bindParam(":em", $em);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);
        $data['profilepic'] = 'http://172.17.254.209/api/'.$data['profilepic'];
        return json_encode($data);
    }
}

?>