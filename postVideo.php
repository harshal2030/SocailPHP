<?php
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/PostClass.php");

if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

    $username = FormSanitizer::sanitizeString($_SERVER['PHP_AUTH_USER']);
    $pw = FormSanitizer::sanitizePassword($_SERVER['PHP_AUTH_PW']);
    $pw = hash("sha512", $salt1 . $pw . $salt2);

    $query = $con->prepare("SELECT * FROM authenticate WHERE username=:un && password=:pw");
    $query->bindParam(":un", $username);
    $query->bindParam(":pw", $pw);

    $query->execute();
    if ($query->rowCount() == 1) {
        $targetDirImg = "uploads/images/posts";
        if (isset($_POST['info'])) {
            $data = json_decode($_POST['info'], true);
            $by = $data['by'];
            $for = $data['for'];

            if ($_FILES['video']) {
                $target_dir2 = $targetDirImg . '/' . rand() . '_' . time() . '.mp4';

                if (move_uploaded_file($_FILES['video']['tmp_name'], $target_dir2)) {
                    $controller = new PostHandler($con);

                    $wasSuccess = $controller->insertPostMedia($by, $for, $target_dir2);
                    if ($wasSuccess) {
                        echo json_encode(array(true));
                    } else echo json_encode(array(false));
                } else {
                    echo json_encode(array(false));
                }
            }
        }
    } else echo json_encode(array("Invalid Credentials!"));
} else {
    header("WWW-Authenticate: Basic realm='Restricted Section'");
    header("HTTP/1.0 401 Unauthorized");
    die("Please enter your username and password");
}
