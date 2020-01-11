<?php
require_once("includes/config.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/FormSanitizer.php");

if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

    $username = FormSanitizer::sanitizeString($_SERVER['PHP_AUTH_USER']);
    $pw = FormSanitizer::sanitizePassword($_SERVER['PHP_AUTH_PW']);
    $pw = hash("sha512", $salt1.$pw.$salt2);

    $query = $con->prepare("SELECT * FROM authenticate WHERE username=:un && password=:pw");
    $query->bindParam(":un", $username);
    $query->bindParam(":pw", $pw);

    $query->execute();
    if ($query->rowCount() == 1) {
        $json = file_get_contents('php://input');

        $obj = json_decode($json, true);
        $name = FormSanitizer::sanitizeString($obj['name']);
        $adm = FormSanitizer::sanitizeString($obj['adm']);
        $email = FormSanitizer::sanitizeEmail($obj['email']);
        $phone = FormSanitizer::sanitizeString($obj['phone']);
        $pw = FormSanitizer::sanitizePassword($obj['password']);
        $dob = $obj['dob'];
        $profilePic = "uploads/images/profilePics/default.png";
    
        $account = new Account($con);
    
        $wasSuccess = $account->register($name, $adm, $email, $phone, $pw, $dob, $profilePic);
        if ($wasSuccess) {
            echo json_encode(array(true));
        }
        else echo json_encode($account->errorArray);
    }
    else echo json_encode(array("Invalid Credentials"));
}
else {
    header("WWW-Authenticate: Basic realm='Restricted Section'");
    header("HTTP/1.0 401 Unauthorized");
    die("Please enter your username and password");
}
?>