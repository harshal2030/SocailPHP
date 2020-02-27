<?php
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/friendClass.php");

if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

    $username = FormSanitizer::sanitizeString($_SERVER['PHP_AUTH_USER']);
    $pw = FormSanitizer::sanitizePassword($_SERVER['PHP_AUTH_PW']);
    $pw = hash("sha512", $salt1.$pw.$salt2);

    $query = $con->prepare("SELECT * FROM authenticate WHERE username=:un && password=:pw");
    $query->bindParam(":un", $username);
    $query->bindParam(":pw", $pw);

    $query->execute();

    $friendController = new FriendHandler($con);

    if ($query->rowCount() == 1) {
        $json = file_get_contents('php://input');

        $obj = json_decode($json, true);
        $userName = FormSanitizer::sanitizeUsername($obj['username']);
        $user = FormSanitizer::sanitizeString($obj['user']);
        $following = FormSanitizer::sanitizeString($obj['following']);
        $followingUserName = FormSanitizer::sanitizeUsername($obj['followingUsername']);

        $wasSuccess = $friendController->addFriend($userName, $user, $followingUserName, $following);
        if ($wasSuccess) {
            echo json_encode(true);
        } else echo http_response_code(500);
    }
}
else {
    header("WWW-Authenticate: Basic realm='Restricted Section'");
    header("HTTP/1.0 401 Unauthorized");
    die("Please enter your username and password");
}
?>