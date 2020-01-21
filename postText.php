<?php
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/PostClass.php");

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

        $controller = new PostHandler($con);
        $data = json_decode($json, true);

        $by = $data['by'];
        $for = $data['for'];
        $title = $data['title'];
        $desc = $data['desc'];

        $wasSuccess = $controller->insertPostData($by, $for, $title, $desc);

        if ($wasSuccess) {
            echo json_encode(array(true));
        } else echo json_encode(array(false));
    }
}
else {
    header("WWW-Authenticate: Basic realm='Restricted Section'");
    header("HTTP/1.0 401 Unauthorized");
    die("Please enter your username and password");
}
?>