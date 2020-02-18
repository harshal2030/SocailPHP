<?php
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/PostClass.php");
require_once("includes/paths.php");

if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

    $username = FormSanitizer::sanitizeString($_SERVER['PHP_AUTH_USER']);
    $pw = FormSanitizer::sanitizePassword($_SERVER['PHP_AUTH_PW']);
    $pw = hash("sha512", $salt1.$pw.$salt2);

    $query = $con->prepare("SELECT * FROM authenticate WHERE username=:un && password=:pw");
    $query->bindParam(":un", $username);
    $query->bindParam(":pw", $pw);

    $query->execute();
    if ($query->rowCount() == 1) {
        if (isset($_GET)) {
            $action = $_GET['action'];
            $controller = new PostHandler($con);
            $data = file_get_contents('php://input');
            $data=json_decode($data, true);

            if ($action == 'FETCH_POST_ACTIONS') {
                echo $controller->fetchPostActions($data['postId'], $data['user']);
            } 
            elseif ($action == 'ADD_LIKE'){
                $controller->addLike($data['postId'], $data['postedBy'], $data['user']);
                echo json_encode(true);
            }
            elseif ($action == 'REMOVE_LIKE') {
                $controller->removeLike($data['postId'], $data['postedBy'], $data['user']);
                echo json_encode(true);
            }
            else echo json_encode("No function exists");
        } else echo json_encode("bad params");
    }
    else echo json_encode(array("Invalid Credentials"));
}
else {
    header("WWW-Authenticate: Basic realm='Restricted Section'");
    header("HTTP/1.0 401 Unauthorized");
    die("Please enter your username and password");
}