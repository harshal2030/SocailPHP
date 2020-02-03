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
        $targetDirImg = LocalPath::$postImagePath;
        $targetDirVideo = LocalPath::$postVideoPath;
        if (isset($_POST['info'])) {
            //storing data in variables
            // default values
            $mediaIncluded = 0; //check if media is included with request, false by default
            $target_dir = '';
            // end default values
            $data = json_decode($_POST['info'], true);
            $id = $data['id'];
            $by = $data['by'];
            $for = $data['for'];
            $title = $data['title'];
            $desc = $data['desc'];

            $controller = new PostHandler($con);

            if (isset($_FILES['image'])) {
                $mediaIncluded = 1;
                $target_dir = $targetDirImg . rand() . '_' . time() . '.jpeg';
    
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_dir)) {

                    $wasSuccess = $controller->insertPostData($id, $by, $for, $title, $desc, $mediaIncluded, $target_dir);
                    if ($wasSuccess) {
                        echo json_encode(array(true));
                    } else echo json_encode(array(false));
                } else {
                    echo json_encode(array(false));
                }
            } elseif(isset($_FILES['video'])) {
                $mediaIncluded = 1;
                $target_dir = $targetDirImg . rand() . '_' . time() . '.mp4';
    
                if (move_uploaded_file($_FILES['video']['tmp_name'], $target_dir)) {

                    $wasSuccess = $controller->insertPostData($id, $by, $for, $title, $desc, $mediaIncluded, $target_dir);
                    if ($wasSuccess) {
                        echo json_encode(array(true));
                    } else echo json_encode(array(false));
                } else {
                    echo json_encode(array(false));
                }
            } else {
                $wasSuccess = $controller->insertPostData($id, $by, $for, $title, $desc, $mediaIncluded, $target_dir);

                if ($wasSuccess) {
                    echo json_encode(array(true));
                } else echo json_encode(array(false));
            }
        }
    }
    else echo json_encode(array("Invalid Credentials"));
}
else {
    header("WWW-Authenticate: Basic realm='Restricted Section'");
    header("HTTP/1.0 401 Unauthorized");
    die("Please enter your username and password");
}
