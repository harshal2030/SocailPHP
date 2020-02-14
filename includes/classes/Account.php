<?php
class Account {

    private $salt1="$%^ ()";
    private $salt2="#*& []";
    private $con;
    public $errorArray = array();
    
    public function __construct($con) {
        $this->con = $con;
    }

    public function register($n, $un, $adm, $em, $phoneNum, $pw, $dob, $profilePic) {
        $this->validateName($n);
        $this->validateUsername($un);
        $this->validateAdm($adm);
        $this->validateEmail($em);
        $this->validatephone($phoneNum);
        $this->validatePassword($pw);

        if (empty($this->errorArray)) {
            $this->insertUserDetails($n, $un, $adm, $em, $phoneNum, $pw, $dob, $profilePic);
            return true;
        }
        else return false;
    }

    private function validateAdm($adm) {
        if (!ctype_digit($adm)) {
            array_push($this->errorArray, Constants::$onlyDigits);
            return;
        }

        if (strlen($adm) < 3 || strlen($adm) > 5) {
            array_push($this->errorArray, Constants::$invalidAdm);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE admission=:adm");
        $query->bindParam(":adm", $adm);

        $query->execute();

        if ($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$admTaken);
        }
    }

    private function validateUsername($un) {

        if (strlen($un) < 2 || strlen($un) > 20) {
            array_push($this->errorArray, Constants::$usernameLength);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:un");
        $query->bindParam(':un', $un);
        $query->execute();

        if ($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$usernameTaken);
            return;
        }
    }

    private function validatephone($num) {
        if (!ctype_digit($num)) {
            array_push($this->errorArray, Constants::$onlyDigits);
            return;
        }

        if (strlen($num) != 10) {
            array_push($this->errorArray, Constants::$invalidPhone);
        }
    }

    private function validateName($n) {
        if(strlen($n) > 50 || strlen($n) < 4) {
            array_push($this->errorArray, Constants::$NameCharacters);
        }
    }

    private function validateEmail($em) {
        if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$emailInvalid);
            return;
        }

        $query = $this->con->prepare("SELECT email FROM users WHERE email=:em");
        $query->bindParam(":em", $em);
        $query->execute();

        if ($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$emailTaken);
        }
    }

    private function validatePassword($pw) {
        if(strlen($pw)<5) {
            array_push($this->errorArray, Constants::$passwordLength);
        } 
    }

    public function insertUserDetails($n, $un, $adm, $em, $phoneNum, $pw, $dob, $profilePic) {
        $pw=hash("sha512", $this->salt1.$pw.$this->salt2);

        $query = $this->con->prepare("INSERT INTO users(name, username, admission, email, DOB, password, 
                                phone, profilepic) VALUES(:nm, :un, :adm, :em, :dob, :pw, :ph, :ppc)");
        $query->bindParam(':nm', $n);
        $query->bindParam(':un', $un);
        $query->bindParam(':adm', $adm);
        $query->bindParam(':em', $em);
        $query->bindParam(':dob', $dob);
        $query->bindParam(':pw', $pw);
        $query->bindParam(':ph', $phoneNum);
        $query->bindParam(':ppc', $profilePic);

        return $query->execute();
    }

    public function login($em, $pw){
        $pw = hash("sha512", $this->salt1.$pw.$this->salt2);
        $query = $this->con->prepare("SELECT username FROM users WHERE email=:em AND password=:pw");
        $query->bindParam(":em", $em);
        $query->bindParam(":pw", $pw);
        $query->execute();

        if ($query->rowCount() == 1){
            return json_encode(array(true, $query->fetch(PDO::FETCH_ASSOC)));
        }
        return false;
    }
}
?>